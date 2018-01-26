<?php

Route::group(['prefix' => 'admin'], function (){
    Route::get('dashboard', [
        'uses' => 'AdminController@dashboard',
        'as' => 'admin.dashboard'
    ]);
});