<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Support\Role;
use App\Prodi;

class RootSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => '15051204043',
            'nama' => 'Rafy Aulia Akbar',
            'password' => bcrypt('secret'),
            'role' => Role::ROOT,
            'prodi_id' => Prodi::findByName('Teknik Informatika')->id,
        ]);

        User::create([
            'id' => '15051204038',
            'nama' => 'M Iskandar Java',
            'password' => bcrypt('secret'),
            'role' => Role::ROOT,
            'prodi_id' => Prodi::findByName('Teknik Informatika')->id,
        ]);

        User::create([
            'id' => '15051204010',
            'nama' => 'Bagas MHH',
            'password' => bcrypt('secret'),
            'role' => Role::ROOT,
            'prodi_id' => Prodi::findByName('Teknik Informatika')->id,
        ]);

        User::create([
            'id' => '123450',
            'nama' => 'Naim Rochmawati, S.Kom., M.T.',
            'password' => bcrypt('secret'),
            'role' => Role::ROOT,
            'prodi_id' => Prodi::findByName('Teknik Informatika')->id,
        ]);

        User::create([
            'id' => '123451',
            'nama' => 'Andi Iwan Nurhidayat, S.Kom., M.T.',
            'password' => bcrypt('secret'),
            'role' => Role::ROOT,
            'prodi_id' => Prodi::findByName('Teknik Informatika')->id,
        ]);
    }
}
