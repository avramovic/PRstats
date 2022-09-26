<?php

Route::get('/', 'HomeController@index');

Route::get('clans', ['as' => 'clans', 'uses' => 'ClanController@index']);
Route::get('clan/{id}/{slug}', ['as' => 'clan', 'uses' => 'ClanController@show']);

Route::get('servers', ['as' => 'servers', 'uses' => 'ServerController@index']);
Route::get('server/{id}/{slug}', ['as' => 'server', 'uses' => 'ServerController@show']);
Route::get('match/{id}/{map}', ['as' => 'match', 'uses' => 'ServerController@match']);

Route::get('maps', ['as' => 'maps', 'uses' => 'MapController@index']);
Route::get('map/{id}/{slug}', ['as' => 'map', 'uses' => 'MapController@show']);

Route::get('players', ['as' => 'players', 'uses' => 'PlayerController@index']);
Route::get('player/find', ['as' => 'player.findByName', 'uses' => 'PlayerController@findByName']);
Route::get('player/{pid}/{slug}', ['as' => 'player', 'uses' => 'PlayerController@show']);
Route::get('notifications', ['as' => 'notifications', 'uses' => 'HomeController@notifications']);
Route::post('player/{player}/toggle', ['as' => 'player.toggle', 'uses' => 'PlayerController@toggleVisibility']);


Route::group(['prefix' => 'json'], function() {
    Route::post('search', ['as' => 'search', 'uses' => 'HomeController@search']);
    Route::post('subscribe', ['as' => 'players.subscribe', 'uses' => 'PlayerController@toggleSubscribe']);
    Route::post('subscribe/check', ['as' => 'players.subscribe.check', 'uses' => 'PlayerController@checkSubscription']);
    Route::post('notifications/get', ['as' => 'notifications.get', 'uses' => 'HomeController@getNotifications']);
    Route::post('login', ['as' => 'login.request', 'uses' => 'UserController@loginRequest']);

});

Route::group(['prefix' => 'claim', 'as' => 'claim.'], function() {
    Route::get('/', ['as' => 'index', 'uses' => 'ClaimController@index']);
    Route::post('{pid}', ['as' => 'store', 'uses' => 'ClaimController@store']);
    Route::get('{uuid}', ['as' => 'show', 'uses' => 'ClaimController@show']);

});

Route::get('login/{id}', ['as' => 'login', 'uses' => 'UserController@login']);
Route::get('logout', ['as' => 'logout', 'uses' => 'UserController@logout']);
Route::get('user/{id}/{slug}', ['as' => 'user', 'uses' => 'UserController@show']);

Route::get('{slug}', ['as' => 'player.short', 'uses' => 'PlayerController@shortUrl']);