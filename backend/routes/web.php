<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web router for your application. These
| router are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');



Route::get('/email-confirm/{code}', 'Auth\RegisterController@confirm')->name('auth.confirm');
Route::get('/password/reset/{code}', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset.token');

Route::get('/home', 'HomeController@index')->name('home');
