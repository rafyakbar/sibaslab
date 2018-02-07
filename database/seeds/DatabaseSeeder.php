<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->call('FakultasJurusanProdiSeeder');
        $this->call('UserSeeder');
        $this->call('MahasiswaSeeder');
        $this->call('KonfirmasiSeeder');
        $this->call('RootSeeder');
        $this->call('LabSeeder');
    }
}
