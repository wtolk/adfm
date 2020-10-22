<?php
Route::group(['namespace' => '\Wtolk\Adfm\Controllers'], function () {
    Route::get('/pages', 'PageController@index')->name('adfm.pages.index');
    Route::get('/pages/create', 'PageController@create')->name('adfm.pages.create');
    Route::post('/pages', 'PageController@store')->name('adfm.pages.store');
    Route::get('/pages/{id}/edit', 'PageController@edit')->name('adfm.pages.edit');
    Route::match(['put', 'patch'],'/pages/{id}', 'PageController@update')->name('adfm.pages.update');
    Route::delete('/pages/{id}', 'PageController@destroy')->name('adfm.pages.destroy');
//Route::get('/pages/{id}/clone', 'PageController@clone');

});

Route::get('/', 'PageController@showMainPage');

/* Роуты админки сгенерированные автоматически для Wtolk\Adfm\Controllers */
Route::group(['namespace' => 'Wtolk\Adfm\Controllers'], function () {
    Route::get('/menus', 'MenuController@index')->name('adfm.menus.index');
    Route::get('/menus/create', 'MenuController@create')->name('adfm.menus.create');
    Route::post('/menus', 'MenuController@store')->name('adfm.menus.store');
    Route::get('/menus/{id}/edit', 'MenuController@edit')->name('adfm.menus.edit');
    Route::match(['put', 'patch'],'/menus/{id}', 'MenuController@update')->name('adfm.menus.update');
    Route::delete('/menus/{id}', 'MenuController@destroy')->name('adfm.menus.destroy');
});
