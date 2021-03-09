<?php

Route::get('/', 'HomeController@index');
Route::post('search', ['as' => 'search', 'uses' => 'HomeController@search']);

Route::get('clans', ['as' => 'clans', 'uses' => 'ClanController@index']);
Route::get('clan/{id}/{slug}', ['as' => 'clan', 'uses' => 'ClanController@show']);

Route::get('servers', ['as' => 'servers', 'uses' => 'ServerController@index']);
Route::get('server/{id}/{slug}', ['as' => 'server', 'uses' => 'ServerController@show']);
Route::get('match/{id}/{map}', ['as' => 'match', 'uses' => 'ServerController@match']);

Route::get('players', ['as' => 'players', 'uses' => 'PlayerController@index']);
Route::get('player/{pid}/{slug}', ['as' => 'player', 'uses' => 'PlayerController@show']);
Route::get('search', ['as' => 'players.search', 'uses' => 'PlayerController@search']);
Route::get('{slug}', ['as' => 'player.short', 'uses' => 'PlayerController@shortUrl']);