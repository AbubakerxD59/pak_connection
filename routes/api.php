<?php

use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PackageController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post("phone-verification/generate", "generateCode");
    Route::post("phone-verification/verify", "verifyCode");
    Route::post("password-reset/email", "verifyEmail");
    Route::post("password-reset/reset", "resetPassword");
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post("logout", "logout");
    });
    Route::controller(HomeController::class)->group(function () {
        Route::get("home", "home");
        Route::get("profile", "profile");
        Route::post("update-profile", "updateProfile");
    });
    Route::prefix("packages/")->controller(PackageController::class)->group(function () {
        Route::get("get", "get");
        Route::get("get-services", "getServices");
        Route::get("get-fields", "getFields");
    });
    Route::prefix("service/")->controller(ServiceController::class)->group(function () {
        Route::get("tracking", "tracking");
    });
    Route::post("checkout", [HomeController::class, "checkout"]);
    Route::prefix("chat/")->controller(ChatController::class)->group(function () {
        Route::post("send-message", "sendMessage");
        Route::get("history", "getChatHistory");
    });
});
