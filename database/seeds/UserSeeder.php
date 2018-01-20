<?php

use Illuminate\Database\Seeder;
use App\Jurusan;
use App\User;
use App\Support\Role;

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
        $counter = 0;
        User::create([
            'id' => $idkalab++.'0',
            'nama' => $faker->unique()->name,
            'password' => bcrypt('secret'),
            'role' => Role::KALAB,
            'prodi_id' => Jurusan::findByName('Teknik Informatika')->getRelasiProdi()->first()->id,
        ]);
        foreach (Jurusan::all() as $jurusan){
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
            }
            $counter = 0;
        }
    }
}
