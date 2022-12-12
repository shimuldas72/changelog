<?php

Route::group(['namespace' => 'Sdas\Changelog\Http\Controllers'], function() {
    Route::get('changelog', 'ChangeLogController@index')->name('contact');
});