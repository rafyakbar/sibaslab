<?php

use Illuminate\Database\Seeder;
use App\Support\ApiUnesa;
use App\Fakultas;
use App\Jurusan;
use App\Prodi;
use App\Mahasiswa;

class ApiUnesaMahasiswaFakultasJurusanProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (ApiUnesa::getFakultasProdi() as $keyfakuktas => $fakultas){
            if (strtolower($fakultas->nama) == 'teknik'){
                $f = Fakultas::create([
                    'nama' => $fakultas->nama
                ]);
                $j = Jurusan::create([
                    'nama' => $f->nama,
                    'fakultas_id' => $f->id
                ]);
                foreach ($fakultas->child as $keyprodi => $prodi){
                    $p = Prodi::create([
                        'nama' => $prodi,
                        'jurusan_id' => $j->id
                    ]);
                    foreach (ApiUnesa::getMahasiswaPerProdi($keyprodi) as $keymhs => $mhs){
                        Mahasiswa::create([
                            'id' => $keymhs,
                            'prodi_id' => $p->id,
                            'nama' => $mhs->nama_mahasiswa,
                            'ipk' => $mhs->aktivitas_kuliah->ipk,
                            'ta' => $mhs->n_skripsi->n,
                            'password' => bcrypt($keymhs)
                        ]);
                    }
                }
            }
        }
    }
}
