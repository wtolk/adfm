<?php

Route::group(['namespace' => 'Wtolk\Adfm\Controllers\Site'], function () {
    Route::get('/r', 'PageController@showMainPage')->name('adfm.main-page');
});
