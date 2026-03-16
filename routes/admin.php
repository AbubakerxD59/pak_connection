<?php

use App\Http\Controllers\FeatureController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FieldController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\BookServiceController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Admin panel routes. Not accessible on the portal domain.
|
*/

Route::group(['middleware' => ['block_user_on_backoffice', 'guest']], function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('showLogin');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware(['block_user_on_backoffice', 'auth', 'is_admin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class)->except('show');
    Route::controller(UserController::class)->prefix('users/')->name('users.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
    });

    // Roles
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
        Route::get('dashboard-order-dataTable', 'dashOrderDataTable')->name('dashboard.order.dataTable');
        Route::get('order-payments', 'viewOrderPayments')->name('view.order.payments');
        Route::get('services-payments', 'viewBookServicePayments')->name('view.order.book.service');
        Route::get('dashboard-service-dataTable', 'dashServiceDataTable')->name('dashboard.service.dataTable');
        Route::get('order-earnings', 'viewOrderEarnings')->name('view.order.earnings');
        Route::get('services-earnings', 'viewBookServiceEarnings')->name('view.book.service.earnings');
    });

    // Booked Services
    Route::resource('booked-services', BookServiceController::class)->except('show');
    Route::controller(BookServiceController::class)->prefix('booked-services/')->name('booked-services.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
        Route::get("view", "view")->name("view");
        Route::post('/booked-services/create-invoice/', 'createInvoice')->name('createInvoice');
        Route::post('/requet-deposit', 'requestDeposit')->name('requestDeposit');
        Route::post('/upload-schedule', 'uploadSchedule')->name('uploadSchedule');
        Route::get('all-book-services', 'index')->name('view.bookservice');
        Route::post('booked-service-pdfs/store', 'uploadBookServicePDF')->name('upload-pdfs');
        Route::get('bookservice-pdf-datatable', 'bookServicePDFDatatable')->name('pdf.dataTable');
        Route::delete('bookservice-pdf-delete/{id}', 'destroyBookServicePDF')->name('pdf.delete');
        Route::post('send-pdfs-email', 'sendBookServicePDFEmail')->name('send-pdf-email');
    });

    Route::resource('chats', ChatController::class)->except("show");
    Route::controller(ChatController::class)->prefix('chats/')->name('chats.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
        Route::get('view/messages', 'viewMessages')->name('view.messages');
        Route::post('new/messages', 'newMessages')->name('new.message');
        Route::get('pending/count', 'pendingCount')->name('pending.count');
    });

    // Verification
    Route::controller(AdminVerificationController::class)->prefix('verification/')->name('verification.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('dataTable', 'dataTable')->name('dataTable');
        Route::get('user/{userId}/documents', 'viewUserDocuments')->name('user.documents');
        Route::post('approve/{id}', 'approve')->name('approve');
        Route::post('reject/{id}', 'reject')->name('reject');
    });

    // Settings
    Route::resource('settings', \App\Http\Controllers\Admin\SettingController::class)->except('show');
    Route::controller(\App\Http\Controllers\Admin\SettingController::class)->prefix('settings/')->name('settings.')->group(function () {
        Route::get('dataTable', 'dataTable')->name('dataTable');
    });
});
