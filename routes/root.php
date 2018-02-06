<?php

Route::group(['prefix' => 'root'], function () {

    Route::namespace('Page')->group(function () {

        Route::get('dashboard', [
            'uses' => 'RootController@dashboard',
            'as' => 'root.dashboard'
        ]);

    });

});