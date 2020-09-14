<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Api\UserController;


Route::group(['prefix' => 'auth','middleware' => ['cors', 'json.response'],], function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', [UserController::class, 'logout']);
        Route::get('user',  [UserController::class, 'user']);       
    });
});




Route::group(['middleware' => ['cors', 'json.response']], function () {
    //Users
    Route::get('users',  [UserController::class, 'all']);
    Route::get('users/{id}',  [UserController::class, 'single']);
    Route::put('users/{id}',  [UserController::class, 'update']);
    Route::delete('users/{id}',  [UserController::class, 'delete']);

    //Projects
    Route::get('projects',  [ProjectController::class, 'all']);

});

