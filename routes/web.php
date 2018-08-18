<?php

//usuario
Route::get('aplicacion','PrincipalController@index');
Route::get('registrarestudiante','UsuarioController@clienteget');
Route::get('listaestudiante','UsuarioController@listaestudiantes');
Route::get('listachofer','UsuarioController@listachoferes');
Route::get('registrarruta','UsuarioController@getregistrarrutas');
Route::post('estudiante', ['as' =>'estudiante', 'uses' => 'UsuarioController@estudianteregistrar']);
Route::post('ruta', ['as' =>'ruta', 'uses' => 'UsuarioController@rutaregistrar']);

// login

Route::get('/', ['as' => 'auth/login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('login', ['as' =>'login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);



Route::get('/home', 'HomeController@index')->name('home');
