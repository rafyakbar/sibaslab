<?php

use Illuminate\Database\Seeder;
use App\Jurusan;
use App\Mahasiswa;
use App\Support\Role;
use App\Fakultas;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        foreach (Fakultas::all() as $fakultas){
            foreach ($fakultas->getJurusan() as $jurusan){
                foreach ($jurusan->getProdi() as $prodi){
                    for ($c = 0; $c < rand(20,35); $c++){
                        Mahasiswa::create([
                            'id' => rand(12, 14).$faker->unique()->numerify('#########'),
                            'nama' => $faker->unique()->name,
                            'prodi_id' => $prodi->id,
                            'password' => bcrypt('secret')
                        ]);
                    }
                }
            }
        }
    }
}
