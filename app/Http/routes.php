<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'Home@index');


Route::get('clan/{id}/{slug}', ['as' => 'clan', 'uses' => 'Home@clan']);
Route::get('player/{pid}/{slug}', ['as' => 'player', 'uses' => 'Home@player']);
Route::get('server/{id}/{slug}', ['as' => 'server', 'uses' => 'Home@server']);
