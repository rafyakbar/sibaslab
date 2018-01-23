<?php

Route::namespace('Page')->group(function () {

    Route::get('daftar/mahasiswa', [
        'uses' => 'KasublabController@daftarMahasiswa',
        'as' => 'kasublab.daftar.mahasiswa'
    ]);

    Route::get('pengaturan', [
        'uses' => 'KasublabController@pengaturan',
        'as' => 'kasublab.pengaturan'
    ]);

});

Route::post('kasublab/tambah', [
    'uses' => 'KasublabController@tambah',
    'as' => 'kasublab.tambah'
]);

Route::post('kasublab/hapus', [
    'uses' => 'KasublabController@hapus',
    'as' => 'kasublab.hapus'
]);

Route::prefix('surat')->group(function () {

    Route::post('setuju', [
        'uses' => 'MahasiswaController@setujuiSurat',
        'as' => 'surat.setuju'
    ]);

    Route::post('tolak', [
        'uses' => 'MahasiswaController@tolakSurat',
        'as' => 'surat.tolak'
    ]);

});

