<?php

Route::namespace('Page')->group(function () {

    Route::get('daftar/mahasiswa', [
        'uses' => 'KasublabController@daftarMahasiswa',
        'as' => 'kasublab.daftar.mahasiswa'
    ]);
    
    Route::get('daftar/kasublab', [
        'uses' => 'KasublabController@daftarKasublab',
        'as' => 'kasublab.daftar.kasublab'
    ]);

    Route::get('pengaturan', [
        'uses' => 'KasublabController@pengaturan',
        'as' => 'kasublab.pengaturan'
    ]);

});

Route::group(['prefix' => 'ajax'], function () {

    Route::post('loadmore/mahasiswa', [
        'uses' => 'MahasiswaController@loadMoreMahasiswa',
        'as' => 'kasublab.loadmore.mahasiswa'
    ]);

    Route::group(['prefix' => 'daftar'], function () {

        Route::post('belum/menyetujui', [
            'uses' => 'MahasiswaController@getDaftarBelumMenyetujui',
            'as' => 'kasublab.daftar.belum.setuju'
        ]);

        Route::post('menanggapi', [
            'uses' => 'MahasiswaController@getDaftarBelumMenanggapi',
            'as' => 'kasublab.daftar.belum.menanggapi'
        ]);

        Route::post('setuju', [
            'uses' => 'MahasiswaController@getDaftarMenyetujui',
            'as' => 'kasublab.daftar.setuju'
        ]);

    });

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

Route::post('lihat/catatan', [
    'uses' => 'MahasiswaController@lihatCatatan',
    'as' => 'kasublab.lihat.catatan'
]);

