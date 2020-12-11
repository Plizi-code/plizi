<?php

Route::post('login', 'App\Http\Controllers\Admin\LoginController@authenticate');

Route::get('login', ['as' => 'admin.login', 'uses' => 'App\Http\Controllers\Admin\LoginController@login']);
Route::get('logout', ['as' => 'admin.logout', 'uses' => 'App\Http\Controllers\Admin\LoginController@logout']);

Route::get('', ['as' => 'admin.dashboard', 'uses' => 'App\Http\Controllers\Admin\LoginController@dashboard']);

Route::get('information', ['as' => 'admin.information', 'uses' => 'App\Http\Controllers\Admin\LoginController@information']);
