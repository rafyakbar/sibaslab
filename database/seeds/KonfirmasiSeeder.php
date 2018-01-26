<?php

use Illuminate\Database\Seeder;
use App\Fakultas;
use App\Support\Role;
use Faker\Factory;

class KonfirmasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        foreach (Fakultas::all() as $fakultas){
            foreach ($fakultas->getJurusan() as $jurusan){
                foreach ($jurusan->getMahasiswa() as $mahasiswa){
                    $konfirmasi = rand(0,1);
                    $dosen = $jurusan->getRelasiUser();
                    $kasublabs = $dosen->whereNotIn('role', [Role::KALAB])->get();
                    $waktu = $faker->dateTimeThisMonth;
                    if ($konfirmasi){
                        foreach ($dosen->get() as $d){
                            $d->getRelasiMahasiswa()->attach($mahasiswa, [
                                'disetujui' => true,
                                'created_at' => $waktu,
                                'updated_at' => $waktu
                            ]);
                        }
                        $mahasiswa->konfirmasi = true;
                        $mahasiswa->save();
                    }
                    else{
                        foreach ($kasublabs as $kasublab){
                            $disetujui = rand(0,1);
                            $kasublab->getRelasiMahasiswa()->attach($mahasiswa, [
                                'disetujui' => $disetujui,
                                'catatan' => ($disetujui) ? null : $faker->text,
                                'created_at' => $waktu,
                                'updated_at' => $waktu
                            ]);
                        }
                    }
                }
            }
        }
    }
}
