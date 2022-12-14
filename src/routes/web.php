<?php

Route::group(['namespace' => 'Sdas\Changelog\Http\Controllers', 'prefix' => 'changelog', 'as' => 'changelog.'], function() {
    Route::get('', 'ChangeLogController@index')->name('list');
    Route::get('ajax', 'ChangeLogController@ajaxList')->name('ajaxList');
    Route::get('detail/{id}', 'ChangeLogController@detail')->name('detail');
    Route::get('timeline/{id}', 'ChangeLogController@timeline')->name('timeline');
    Route::get('timeline-details/{id}', 'ChangeLogController@timelineDetails')->name('timeline.details');
});