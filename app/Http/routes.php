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

Route::get('servers', ['as' => 'servers', 'uses' => 'Home@servers']);
Route::get('players', ['as' => 'players', 'uses' => 'Home@players']);
Route::get('clans', ['as' => 'clans', 'uses' => 'Home@clans']);
Route::post('players', ['as' => 'players.search', 'uses' => 'Home@playerSearch']);
Route::post('clans', ['as' => 'clans.search', 'uses' => 'Home@clanSearch']);
Route::get('match/{id}/{map}', ['as' => 'match', 'uses' => 'Home@matchDetails']);


