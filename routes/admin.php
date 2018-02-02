<?php

Route::group(['prefix' => 'admin'], function (){
    Route::get('dashboard', [
        'uses' => 'AdminController@dashboard',
        'as' => 'admin.dashboard'
    ]);

    Route::get('kalabkasublab', [
        'uses' => 'AdminController@kalabKasublab',
        'as' => 'admin.kalabkasublab'
    ]);

    Route::get('mahasiswa/filter/{jurusan}', [
        'uses' => 'AdminController@mahasiswa',
        'as' => 'admin.mahasiswa'
    ]);

    Route::patch('mahasiswa/reset', [
        'uses' => 'AdminController@resetMahasiswa',
        'as' => 'admin.mahasiswa.reset'
    ]);

    Route::get('etc/getmahasiswa/{jurusan}', [
        'uses' => 'AdminController@getMahasiswa',
        'as' => 'admin.etc.getmahasiswa'
    ]);

    Route::get('etc/getprodi/{jurusan_id}', [
        'uses' => 'PublicController@getProdi',
        'as' => 'admin.etc.getjurusan'
    ]);

    Route::put('kalabkasublab/tambah', [
        'uses' => 'AdminController@tambahKalabKasublab',
        'as' => 'admin.kalabkasublab.tambah'
    ]);

    Route::delete('kalabkasublab/hapus', [
        'uses' => 'AdminController@hapusKalabKasublab',
        'as' => 'admin.kalabkasublab.hapus'
    ]);

    Route::patch('kalabkasublab/reset', [
        'uses' => 'AdminController@resetUser',
        'as' => 'admin.kalabkasublab.reset'
    ]);
});