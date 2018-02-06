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

    Route::get('login', [
        'uses' => 'MahasiswaController@login',
        'as' => 'mahasiswa.login'
    ]);

    Route::post('login', [
        'uses' => 'Auth\LoginController@loginMahasiswa',
        'as' => 'mahasiswa.login.proses'
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

Route::get('qr', function () {
    return view('test.qr');
});