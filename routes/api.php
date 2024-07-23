<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::post('user/{user}/subscription', [SubscriptionController::class, 'store']);
    Route::put('user/{user}/subscription/{subscription}', [SubscriptionController::class, 'update']);
    Route::delete('user/{user}/subscription/{subscription}', [SubscriptionController::class, 'destroy']);
    Route::post('user/{user}/transaction', [SubscriptionController::class, 'createTransaction']);

    Route::get('user/{user}', [UserController::class, 'show']);
});
