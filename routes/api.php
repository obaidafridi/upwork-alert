<?php

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});



Route::middleware('auth:sanctum')->group(function () {
    Route::post('change-password', [RegisterController::class, 'changePassword']);
    Route::post('/user/profile-image', [UserController::class, 'updateProfileImage']);


    Route::post('/user/keys', [KeyController::class, 'store']);
    Route::delete('/user/keys/{key}', [KeyController::class, 'destroy']);

    Route::put('/user/profile', [UserController::class, 'updateProfile']);


    Route::post('/logout', [RegisterController::class, 'logout']);

});



// user
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
