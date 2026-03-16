<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MemberController;
use App\Http\Controllers\Frontend\VerificationController as FrontendVerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Landing route
Route::get('/', function (Request $request) {
    $backofficeDomain = config('app.backoffice_domain');
    if ($backofficeDomain && $request->getHost() === $backofficeDomain) {
        return redirect()->to(rtrim(config('app.url'), '/') . '/login');
    }
});


// Member password routes
Route::prefix('password')->middleware('block_admin_on_portal')->name('password.')->group(function () {
    Route::get('forgot', [PasswordController::class, 'showLinkRequestForm'])->name('request');
    Route::post('email', [PasswordController::class, 'sendResetLinkEmail'])->name('send_link');
    Route::get('/change-password', [PasswordController::class, 'visitPasswordLink'])->name('visit_link');
    Route::post('reset', [PasswordController::class, 'resetPassword'])->name('reset');
});

// Frontend routes

Route::name('frontend.')->middleware('block_admin_on_portal')->group(function () {
    Route::middleware(["frontend"])->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('buy-membership/{id?}', [HomeController::class, 'buyMembership'])->name('buy_memebership');
        Route::post('checkout', [HomeController::class, 'checkout'])->name('checkout');
        Route::get('success', [HomeController::class, 'success'])->name('checkout_success');
        Route::get('member/login', [FrontendAuthController::class, 'showLogin'])->name("showLogin");
        Route::post('member/login', [FrontendAuthController::class, 'login'])->name("login");
    });
    Route::middleware(['is_member'])->controller(MemberController::class)->name('member.')->prefix('member/')->group(function () {
        Route::post('logout', [FrontendAuthController::class, 'logout'])->name("logout");

        // Routes that require verification
        Route::middleware(['verified_member'])->group(function () {
            Route::get('home', 'home')->name('home');
            Route::get('get-fields', 'getFields')->name('getFields');
            Route::post('book-service', "bookService")->name("bookService");
        });

        // Routes accessible without verification
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile-update', 'profileUpdate')->name('profile_update');
        Route::get("tracking", "tracking")->name("tracking");
    });

    // Verification routes for members
    Route::middleware(['is_member'])->controller(FrontendVerificationController::class)->name('member.verification.')->prefix('member/verification/')->group(function () {
        Route::get('status', 'checkStatus')->name('status');
        Route::post('upload', 'upload')->name('upload');
    });
});

// Stripe webhook route
Route::post("webhook", [HomeController::class, 'webhook'])->name('stripe_webhook');
