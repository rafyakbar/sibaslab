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
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('tes', function (){
    return json_encode(\App\Mahasiswa::all());
});

Route::group(['middleware' => 'mobile', 'prefix' => 'mobile'], function (){
    Route::group(['prefix' => 'mahasiswa'], function (){
        Route::get('get/{jurusan_id}', [
            'uses' => 'MahasiswaController@mobileGet',
            'as' => 'mobile.mahasiswa.get'
        ]);
    });
    Route::get('tes', function (){
        return json_encode(\App\Mahasiswa::all());
    });
});