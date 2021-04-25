<?php

Route::prefix('/admin')->middleware(['web', 'auth'])->namespace('App\Http\Controllers\Admin')->group(function () {


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
        Route::get('/menuitems/create/{menu_id?}/{model_name}/{model_id}', 'MenuItemController@createFromModel')->name('adfm.menuitems.createFromModel');
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


    /* Роуты админки сгенерированные автоматически для App\Adfm\Controllers\Admin\UserController */

        Route::get('/users', 'UserController@index')->name('adfm.users.index');
        Route::get('/users/create', 'UserController@create')->name('adfm.users.create');
        Route::post('/users', 'UserController@store')->name('adfm.users.store');
        Route::get('/users/{id}/edit', 'UserController@edit')->name('adfm.users.edit');
        Route::match(['put', 'patch'],'/users/{id}', 'UserController@update')->name('adfm.users.update');
        Route::delete('/users/{id}', 'UserController@destroy')->name('adfm.users.destroy');


});
//Route::get('/setup-adfm', [\Wtolk\Adfm\Controllers\SetupController::class, 'setUpProviders'])->name('adfm.start');


