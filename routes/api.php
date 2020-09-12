<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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


Route::group(['prefix' => 'auth','middleware' => ['cors', 'json.response'],], function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', [UserController::class, 'logout']);
        Route::get('user',  [UserController::class, 'user']);       
    });
});




Route::group(['middleware' => ['cors', 'json.response','auth:api']], function () {
    Route::get('users',  [UserController::class, 'all']);
    Route::get('users/{id}',  [UserController::class, 'single']);
    Route::put('users/{id}',  [UserController::class, 'update']);
    Route::delete('users/{id}',  [UserController::class, 'delete']);
});

