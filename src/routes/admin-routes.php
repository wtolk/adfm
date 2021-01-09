<?php

Route::prefix('/admin')->middleware(['web', 'auth'])->namespace('App\Adfm\Controllers\Admin')->group(function () {


        Route::get('/pages', 'PageController@index')->name('adfm.pages.index');
        Route::get('/pages/create', 'PageController@create')->name('adfm.pages.create');
        Route::post('/pages', 'PageController@store')->name('adfm.pages.store');
        Route::get('/pages/{id}/edit', 'PageController@edit')->name('adfm.pages.edit');
        Route::match(['put', 'patch'],'/pages/{id}', 'PageController@update')->name('adfm.pages.update');
        Route::delete('/pages/{id}', 'PageController@destroy')->name('adfm.pages.destroy');
//Route::get('/pages/{id}/clone', 'PageController@clone');


    /* Роуты админки сгенерированные автоматически для Wtolk\Adfm\Controllers */

        Route::get('/menus', 'MenuController@index')->name('adfm.menus.index');
        Route::get('/menus/create', 'MenuController@create')->name('adfm.menus.create');
        Route::post('/menus', 'MenuController@store')->name('adfm.menus.store');
        Route::get('/menus/{id}/edit', 'MenuController@edit')->name('adfm.menus.edit');
        Route::match(['put', 'patch'],'/menus/{id}', 'MenuController@update')->name('adfm.menus.update');
        Route::delete('/menus/{id}', 'MenuController@destroy')->name('adfm.menus.destroy');


    /* Роуты админки сгенерированные автоматически для Wtolk\Adfm\Controllers */

        Route::get('/menuitems', 'MenuItemController@index')->name('adfm.menuitems.index');
        Route::get('/menuitems/create/{menu_id?}', 'MenuItemController@create')->name('adfm.menuitems.create');
        Route::post('/menuitems', 'MenuItemController@store')->name('adfm.menuitems.store');
        Route::get('/menuitems/{id}/edit', 'MenuItemController@edit')->name('adfm.menuitems.edit');
        Route::match(['put', 'patch'],'/menuitems/{id}', 'MenuItemController@update')->name('adfm.menuitems.update');
        Route::delete('/menuitems/{id}', 'MenuItemController@destroy')->name('adfm.menuitems.destroy');


    /* Роуты админки сгенерированные автоматически для Wtolk\Adfm\Controllers */

        Route::get('/roles', 'RoleController@index')->name('adfm.roles.index');
        Route::get('/roles/create', 'RoleController@create')->name('adfm.roles.create');
        Route::post('/roles', 'RoleController@store')->name('adfm.roles.store');
        Route::get('/roles/{id}/edit', 'RoleController@edit')->name('adfm.roles.edit');
        Route::match(['put', 'patch'],'/roles/{id}', 'RoleController@update')->name('adfm.roles.update');
        Route::delete('/roles/{id}', 'RoleController@destroy')->name('adfm.roles.destroy');

});
//Route::get('/setup-adfm', [\Wtolk\Adfm\Controllers\SetupController::class, 'setUpProviders'])->name('adfm.start');


