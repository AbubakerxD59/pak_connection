<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FieldController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MemberController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\Admin\TransactionController;
use App\Models\BookService;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [AuthController::class, 'index']);
// Route::get('/email-template', function () {
//     return view('emails.deposit_requested');
// });

Route::get('/email-preview', function () {
    $bookedService = BookService::with(['user', 'service'])->first();
    // return view('emails.deposit_requested', [
    return view('emails.deposit_requested', [
        'bookedService' => $bookedService,
        'paymentLink' => 'https://pakconnections.co.uk/pay-deposit/abc123'
    ]);
});




Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('showLogin');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});


Route::prefix('password')->name('password.')->group(function () {
    // Show email form
    Route::get('forgot', [PasswordController::class, 'showLinkRequestForm'])->name('request');

    // Send reset link
    Route::post('email', [PasswordController::class, 'sendResetLinkEmail'])->name('send_link');

    // Show reset password form from email
    // Route::get('reset/{token}', [PasswordController::class, 'visitPasswordLink'])->name('visit_link');
    Route::get('/change-password', [PasswordController::class, 'visitPasswordLink'])->name('visit_link');

    // Submit new password
    Route::post('reset', [PasswordController::class, 'resetPassword'])->name('reset');
});



Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class)->except('show');
    Route::controller(UserController::class)->prefix('users/')->name('users.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
        Route::get("edit/{id}/booked-service", "editBookedService")->name("booked_service.edit");
        Route::put("update/{id}/booked-service", "updateBookedService")->name("booked_service.update");
        Route::delete("delete/{id}/booked-service", "deleteBookedService")->name("booked_service.delete");

        Route::post('/booked-service/invoice/', 'createInvoice')->name('book_service_invoice');
        Route::post('/deposit-payment', 'depositPayment')->name('deposit_payment');
        Route::post('/user-crm-status', 'crmStatusTransaction')->name('user_crm_status');


    });

    //  Roles
    Route::resource('roles', RolesController::class)->except('show');
    Route::controller(RolesController::class)->prefix('roles/')->name('roles.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
        Route::post('assign/permissions/{id?}', 'assignPermissions')->name('assign_permissions');
    });

    // Permissions
    Route::resource('permissions', PermissionController::class)->except('show');
    Route::controller(PermissionController::class)->prefix('permissions/')->name('permissions.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
    });

    // Packages
    Route::resource('packages', PackageController::class)->except('show');
    Route::controller(PackageController::class)->prefix('packages/')->name('packages.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
        Route::post('add-facility', 'addFacility')->name('add_facility');
        Route::get("show-booked-service", "showBookedService")->name("show_booked_service");
    });

    // Features
    Route::resource('features', FeatureController::class)->except('show');
    Route::controller(FeatureController::class)->prefix('features/')->name('features.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
        Route::post("add-field", "addField")->name("addField");
        Route::get("save-order", "saveOrder")->name("saveOrder");
    });

    // Fields
    Route::resource('fields', FieldController::class)->except('show');
    Route::controller(FieldController::class)->prefix('fields/')->name('fields.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
        Route::post('import', 'import')->name('import');
        Route::get("save-order", "saveOrder")->name("saveOrder");
    });

    // Promo Codes
    Route::resource('promo-code', PromoCodeController::class)->except('show');
    Route::controller(PromoCodeController::class)->prefix('promo-code/')->name('promo-code.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
    });

    // Order
    Route::resource('orders', OrderController::class)->except('show');
    Route::controller(OrderController::class)->prefix('orders/')->name('orders.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
    });

    // Transactions
    Route::resource('transactions', TransactionController::class)->except('show');
    Route::controller(TransactionController::class)->prefix('transactions/')->name('transactions.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
    });
});

// Strip routes
Route::name('frontend.')->group(function () {
    Route::middleware(["frontend"])->group(function () {
        Route::get('home', [HomeController::class, 'index'])->name('home');
        Route::get('buy-membership/{id?}', [HomeController::class, 'buyMembership'])->name('buy_memebership');
        Route::post('checkout', [HomeController::class, 'checkout'])->name('checkout');
        Route::get('success', [HomeController::class, 'success'])->name('checkout_success');
        Route::get('member/login', [FrontendAuthController::class, 'showLogin'])->name("showLogin");
        Route::post('member/login', [FrontendAuthController::class, 'login'])->name("login");
    });
    Route::middleware(['is_member'])->controller(MemberController::class)->name('member.')->prefix('member/')->group(function () {
        Route::post('logout', [FrontendAuthController::class, 'logout'])->name("logout");
        Route::get('home', 'home')->name('home');
        Route::get('get-fields', 'getFields')->name('getFields');
        Route::post('book-service', "bookService")->name("bookService");
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile-update', 'profileUpdate')->name('profile_update');
    });
});

// Strip webhook route
Route::post("webhook", [HomeController::class, 'webhook'])->name('stripe_webhook');
