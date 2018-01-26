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

    Route::get('mahasiswa', [
        'uses' => 'AdminController@mahasiswa',
        'as' => 'admin.mahasiswa'
    ]);
});