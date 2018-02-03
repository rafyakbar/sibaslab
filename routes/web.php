<?php

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes...
Route::namespace('Auth')->group(function () {

    Route::get('login', [
        'uses' => 'LoginController@showLoginForm',
        'as' => 'user.login'
    ]);

    Route::post('login', [
        'uses' => 'LoginController@login',
        'as' => 'user.login.proses'
    ]);

    Route::post('logout', [
        'uses' => 'LoginController@logout',
        'as' => 'user.logout'
    ]);

});

Route::group(['prefix' => 'mahasiswa'], function () {

    Route::get('dashboard', [
        'uses'=>'MahasiswaController@dashboard',
        'as'=>'mahasiswa.dashboard'
    ]);

    Route::get('login', [
        'uses' => 'MahasiswaController@login',
        'as' => 'mahasiswa.login'
    ]);

    Route::post('login', [
        'uses' => 'Auth\LoginController@loginMahasiswa',
        'as' => 'mahasiswa.login.proses'
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

Route::get('qr', function () {
    return view('test.qr');
});