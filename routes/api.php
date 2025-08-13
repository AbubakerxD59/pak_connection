<?php

use App\Http\Controllers\Api\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post("phone-verification/generate", [AuthController::class, "generateCode"]);
Route::post("phone-verification/verify", [AuthController::class, "verifyCode"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post("logout", "logout");
        Route::controller(HomeController::class)->group(function () {
            Route::get("home", "home");
        });
        Route::prefix("service/")->controller(ServiceController::class)->group(function(){
            Route::get("tracking", "tracking");
        });
    });
});
