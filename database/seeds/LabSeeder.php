<?php

use Illuminate\Database\Seeder;
use App\Fakultas;
use App\Lab;

class LabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $jurusan_count = 1;
        foreach (Fakultas::all() as $fakultas){
            foreach ($fakultas->getJurusan() as $jurusan){
                $lab = Lab::create([
                    'nama' => $faker->unique()->text(25),
                    'kelas' => 'A'.$jurusan_count.'.0'.rand(1,3).'.0'.rand(1,3)
                ]);
                foreach ($jurusan->getDaftarKasublab() as $kasublab){
                    $kasublab->lab_id = $lab->id;
                    $kasublab->save();
                    if (rand(0,1))
                        $lab = Lab::create([
                            'nama' => $faker->unique()->text(25),
                            'kelas' => 'A'.$jurusan_count.'.0'.rand(1,3).'.0'.rand(1,3)
                        ]);
                }
                $jurusan_count++;
            }
        }
    }
}
