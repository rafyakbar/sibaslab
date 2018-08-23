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
        foreach (ApiUnesa::getFakultasJurusanProdi() as $keyfakuktas => $fakultas){
            if (strtolower($fakultas->nama) == 'teknik'){
                $f = Fakultas::create([
                    'nama' => $fakultas->nama
                ]);
                foreach ($fakultas->child as $keyjurusan => $jurusan){
                    $j = Jurusan::create([
                        'nama' => $jurusan->nama,
                        'fakultas_id' => $f->id
                    ]);
                    foreach ($jurusan->child as $keyprodi => $prodi){
                        $p = Prodi::create([
                            'nama' => $prodi,
                            'jurusan_id' => $j->id
                        ]);
//                        $filteredmhs = ApiUnesa::getMahasiswaPerProdi($keyprodi)
//                            ->filter(function ($value, $key){
//                                return !is_null($value->n_skripsi->n);
//                            });
                        $filteredmhs = ApiUnesa::getMahasiswaPerProdi($keyprodi);
                        $i = 1;
                        foreach ($filteredmhs as $keymhs => $mhs){
                            $mhs = ApiUnesa::getDetailMahasiswa($keymhs);

                            if ($i == 6)
                                break;

                            if (is_array($mhs->n_skripsi))
                                continue;

                            $i++;

                            $ta = $mhs->n_skripsi->{collect(get_object_vars($mhs->n_skripsi))->keys()[0]}->n;

                            Mahasiswa::create([
                                'id' => $keymhs,
                                'prodi_id' => $p->id,
                                'nama' => $mhs->nama_mahasiswa,
                                'ipk' => $mhs->aktivitas_kuliah->ipk,
                                'email' => $mhs->email,
                                'jk' => $mhs->jenis_kelamin,
                                'ta' => $ta,
                                'mengajukan_pada' => now()->toDateTimeString(),
                                'password' => bcrypt($keymhs)
                            ]);
                        }
                    }
                }
            }
        }
    }
}
