<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'App\Http\Controllers\Api\AuthController@login');

Route::post('/contact-us', 'App\Http\Controllers\Api\ContactController@store');
Route::get('/gallery', 'App\Http\Controllers\Api\GalleryController@clientIndex');

Route::get('images/{filename}', function ($filename) {
    $file = \Illuminate\Support\Facades\Storage::get($filename);
    return response($file, 200)->header('Content-Type', 'image/jpeg');
});

Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'contact-us'], function () {
        Route::get('/', 'App\Http\Controllers\Api\ContactController@index');
        // Route::get('connect', ['as' => 'connect', 'uses' = > 'AccountController@connect']);
    });

    Route::group(['prefix' => 'gallery'], function () {
        Route::get('/', 'App\Http\Controllers\Api\GalleryController@adminIndex');
        Route::post('/', 'App\Http\Controllers\Api\GalleryController@store');
        Route::delete('/{id}', 'App\Http\Controllers\Api\GalleryController@destroy');
    });

    Route::post('/logout', 'App\Http\Controllers\Api\AuthController@logout');
});

// $router->group(['middleware' => 'auth:api'], function () use ($router) {
// });
