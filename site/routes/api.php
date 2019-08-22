<?php

use Illuminate\Http\Request;

/**
 * Endpoints de autenticação
 *
 * Obs.: Login é a única rota não autenticada.
 * Faz parte da lista de exceção definida no controller.
 *
 * Ex.: http://host/api/auth/{{recurso}}
 */
Route::prefix('auth')->group(function () {
    Route::post('/login', 'Api\AuthController@login')->name('Login');
    Route::get('/logout', 'Api\AuthController@logout')->name('Logout');
    Route::get('/refresh', 'Api\AuthController@refresh')->name('Refresh');
    Route::get('/me', 'Api\AuthController@me')->name('Me');
});

/**
 * Endpoints de produtos
 *
 * Ex.: http://host/api/product/{{recurso}}
 */

Route::prefix('product')->group(function () {
    Route::get('/', 'Api\ProductController@index')->name('Read_All');
    Route::post('/', 'Api\ProductController@store')->name('Create');
    Route::get('/{id}', 'Api\ProductController@show')->name('Read');
    Route::put('/{id}', 'Api\ProductController@update')->name('Update');
    Route::delete('/{id}', 'Api\ProductController@destroy')->name('Delete');
});
