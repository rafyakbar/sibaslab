<?php

Route::get('/', function () {
    return view('welcome');
})->name('landing');

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

    Route::get('ajukan',[
        'uses' => 'MahasiswaController@ajukan',
        'as' => 'mahasiswa.ajukan'
    ]);

    Route::post('ajukan', [
        'uses' => 'MahasiswaController@prosesAjukan',
        'as' => 'mahasiswa.ajukan.proses'
    ]);

    Route::get('login', [
        'uses' => 'MahasiswaController@login',
        'as' => 'mahasiswa.login'
    ]);

    Route::post('login', [
        'uses' => 'Auth\LoginController@loginMahasiswa',
        'as' => 'mahasiswa.login.proses'
    ]);
    Route::get('etc/getprodi/{jurusan_id}', [
        'uses' => 'PublicController@getProdi',
        'as' => 'mahasiswa.etc.getjurusan'
    ]);
    Route::get('validasi/{val}', [
        'uses' => 'PublicController@validasi',
        'as' => 'mahasiswa.validasi'
    ]);
});

Route::namespace('Page')->group(function () {

    Route::get('pengaturan', [
        'uses' => 'PengaturanController@index',
        'as' => 'halaman.pengaturan'
    ]);

});

Route::post('ubah/profil', [
    'uses' => 'PengaturanController@ubahProfil',
    'as' => 'ubah.profil'
]);

Route::post('ubah/katasandi', [
    'uses' => 'PengaturanController@ubahKataSandi',
    'as' => 'ubah.kata.sandi'
]);

Route::post('unduh/berkas', [
    'uses' => 'MahasiswaController@unduhBerkas',
    'as' => 'unduh.berkas'
]);

Route::get('qr', function () {
    return view('test.qr');
});

Route::get('tampil/qr/{nim}', [
    'uses' => 'MahasiswaController@tampilQr',
    'as' => 'mahasiswa.tampil.qr'
]);