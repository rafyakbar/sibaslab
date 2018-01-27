<?php

use Illuminate\Database\Seeder;
use App\Jurusan;
use App\User;
use App\Support\Role;
use App\Fakultas;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $idkalab = 1234567891;
        $idadmin = 987654321;
        $counter = 0;
        foreach (Fakultas::all() as $fakultas){
            User::create([
                'id' => $idadmin++,
                'nama' => $faker->unique()->name,
                'password' => bcrypt('secret'),
                'role' => Role::ADMIN,
                'prodi_id' => $fakultas->getRelasiJurusan()->first()->getRelasiProdi()->first()->id
            ]);
            foreach ($fakultas->getJurusan() as $jurusan){
                User::create([
                    'id' => $idkalab++.'0',
                    'nama' => $faker->unique()->name,
                    'password' => bcrypt('secret'),
                    'role' => Role::KALAB,
                    'prodi_id' => $jurusan->getRelasiProdi()->first()->id,
                ]);
                foreach ($jurusan->getProdi() as $prodi){
                    User::create([
                        'id' => $idkalab.++$counter,
                        'nama' => $faker->unique()->name,
                        'password' => bcrypt('secret'),
                        'role' => Role::KASUBLAB,
                        'prodi_id' => $prodi->id,
                    ]);
                    User::create([
                        'id' => $idkalab.++$counter,
                        'nama' => $faker->unique()->name,
                        'password' => bcrypt('secret'),
                        'role' => Role::KASUBLAB,
                        'prodi_id' => $prodi->id,
                    ]);
                }
                $counter = 0;
            }
        }
    }
}
