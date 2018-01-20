<?php

use Illuminate\Database\Seeder;
use App\Jurusan;
use App\Prodi;

class JurusanProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    const TEKNIK = [
        'Teknik Informatika' => [
            'S1 Teknik Informatika',
            'S1 Sistem Informasi',
            'S1 Pendidikan Teknologi Informasi',
            'D3 Manajemen Informatika'
        ],
        'Teknik Mesin' => [
            'S1 Teknik Mesin',
            'D3 Teknik Mesin',
            'S1 Pend Teknik Mesin'
        ],
        'Teknik Elektro' => [
            'S1 Teknik Elektro',
            'S1 Pend. Teknik Elektro',
            'D3 Teknik Listrik',
        ],
        'Teknik Sipil' => [
            'S1 Teknik Sipil',
            'S1 Pend Teknik Bangunan',
            'D3 Teknik Sipil',
            'D3 Manajemen Transportasi'
        ],
        'Pendidikan Kesejahteraan Keluarga' => [
            'S1 Pendidikan Tata Busana',
            'S1 Pendidikan Kesejahteraan Keluarga',
            'S1 Pendidikan Tata Rias',
            'S1 Pendidikan Tata Boga',
            'D3 Tata Boga',
            'D3 Tata Busana'
        ]
    ];

    public function run()
    {
        foreach (static::TEKNIK as $jurusan => $daftarProdi) {
            $id = Jurusan::create([
                'nama' => $jurusan
            ])->id;
            foreach ($daftarProdi as $prodi) {
                Prodi::create([
                    'nama' => $prodi,
                    'jurusan_id' => $id
                ]);
            }
        }
    }
}
