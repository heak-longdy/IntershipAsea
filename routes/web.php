<?php

use App\Events\BookingEvent;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\PaywayController;
use App\Http\Controllers\website\HomeController;

Route::get('/index', function () {
    return view("admin::layout2.index");
    // return view("admin::auth.sign-in");
    // return view("admin::pusher");
//    return view("admin::welcome");
});

// User
Route::prefix('/')
->name('/')
->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
});

Route::get('/pusher', function () {
    return view("admin::pusher");
});

Route::get('booking', [BookingController::class, 'export_booking'])->name('booking');

Route::get('customer-export', [CustomerController::class, 'export_customer'])->name('customer-export');

// Route::group([
//     'prefix' => 'payway',
// ], function () {
//     Route::get('index', [PaywayController::class, 'index']);
// });
// Route::get('payway-form',[PaywayController::class,'payway_form']);
// Route::get('payway-submit', [PaywayController::class, 'paymentSubmit']);