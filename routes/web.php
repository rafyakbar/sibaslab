<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes...
Route::namespace('Auth')->group(function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
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
});