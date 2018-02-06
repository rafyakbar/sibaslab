<?php

Route::group(['prefix' => 'mahasiswa'], function () {

    Route::get('dashboard', [
        'uses'=>'MahasiswaController@dashboard',
        'as'=>'mahasiswa.dashboard'
    ]);

    Route::get('ajukan',[
        'uses' => 'MahasiswaController@ajukan',
        'as' => 'mahasiswa.ajukan'
    ]);
    Route::get('edit', [
        'uses' => 'MahasiswaController@edit',
        'as' => 'mahasiswa.edit'
    ]);
    Route::post('ajukan', [
        'uses' => 'MahasiswaController@prosesAjukan',
        'as' => 'mahasiswa.ajukan.proses'
    ]);
    Route::post('perbaruiBerkas', [
        'uses' => 'MahasiswaController@perbaruiBerkas',
        'as' => 'mahasiswa.berkas.perbarui'
    ]);
    Route::post('perbaruiPassword', [
        'uses' => 'MahasiswaController@perbaruiPassword',
        'as' => 'mahasiswa.password.perbarui'
    ]);
    Route::get('etc/getprodi/{jurusan_id}', [
        'uses' => 'PublicController@getProdi',
        'as' => 'mahasiswa.etc.getjurusan'
    ]);
    Route::get('unduh', [
        'uses' => 'MahasiswaController@unduh',
        'as' => 'mahasiswa.unduh'
    ]);
});