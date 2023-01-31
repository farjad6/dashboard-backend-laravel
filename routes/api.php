<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
    Route::post('send-otp-for-forget-password', 'sendOTPForForgetPassword');
});

Route::group([
    'middleware' => ['auth:api'],
    'namespace' => 'App\\Http\\Controllers'
], function ($router) {
    // Users
    Route::get('users', 'UsersController@index');
    //Invites
    Route::get('invites', 'InvitesController@index');
    Route::post('invites', 'InvitesController@store');
    Route::delete('invites/{id}', 'InvitesController@destroy');
});

// Route::controller(UsersController::class)->group(function () {
    // Route::post('todo', 'store');
    // Route::get('todo/{id}', 'show');
    // Route::put('todo/{id}', 'update');
    // Route::delete('todo/{id}', 'destroy');
// });
