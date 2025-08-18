<?php

use App\Http\Controllers\Api\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PackageController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post("phone-verification/generate", [AuthController::class, "generateCode"]);
Route::post("phone-verification/verify", [AuthController::class, "verifyCode"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post("logout", "logout");
    });
    Route::controller(HomeController::class)->group(function () {
        Route::get("home", "home");
        Route::get("profile", "profile");
        Route::post("update-profile", "updateProfile");
    });
    Route::prefix("packages/")->controller(PackageController::class)->group(function() {
        Route::get("get", "get");
        Route::get("get-services", "getServices");
        Route::get("get-fields", "getFields");
    });
    Route::prefix("service/")->controller(ServiceController::class)->group(function () {
        Route::get("tracking", "tracking");
    });
});
