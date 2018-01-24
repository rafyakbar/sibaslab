<?php

use Illuminate\Database\Seeder;
use App\Jurusan;
use App\Mahasiswa;
use App\Support\Role;

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
        foreach (Jurusan::all() as $jurusan){
            foreach ($jurusan->getProdi() as $prodi){
                for ($c = 0; $c < rand(10,30); $c++){
                    Mahasiswa::create([
                        'id' => '14'.$faker->unique()->numerify('#########'),
                        'nama' => $faker->unique()->name,
                        'prodi_id' => $prodi->id,
                        'password' => bcrypt('secret')
                    ]);
                }
            }
        }
    }
}
