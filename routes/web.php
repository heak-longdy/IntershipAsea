<?php

use App\Events\BookingEvent;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\PaywayController;
use App\Http\Controllers\website\HomeController;

// Route::get('/index', function () {
//     return view("admin::layout2.index");
//     // return view("admin::auth.sign-in");
//     // return view("admin::pusher");
//     //    return view("admin::welcome");
// });

// User
// Route::group(['prefix' => '/','as'=>'/'], function () {
//     Route::get('/', [HomeController::class, 'index'])->name('index');
//     Route::get('/job', [HomeController::class, 'job'])->name('job');
//     Route::get('/job/detail/{id}', [HomeController::class, 'jobDetail'])->name('job-detail');
//     Route::get('/apply/form', [HomeController::class, 'applyForm'])->name('apply-form');
//     Route::post('/apply', [HomeController::class, 'apply'])->name('apply');
//     Route::get('contact', [HomeController::class, 'contact'])->name('contact');
// });

Route::group(
    [
        'prefix' => '/',
        'as' => 'web-'
    ],
    function () {
        Route::get('/', [HomeController::class, 'index'])->name('index');
        Route::get('/job', [HomeController::class, 'job'])->name('job');
        Route::get('/job/detail/{id}', [HomeController::class, 'jobDetail'])->name('job-detail');
        Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
        Route::get('/blog/detail/{id}', [HomeController::class, 'blogDetail'])->name('blog-detail');
        Route::get('/apply/form', [HomeController::class, 'applyForm'])->name('apply-form');
        Route::post('/apply', [HomeController::class, 'apply'])->name('apply');
        Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
        Route::get('/our-service', [HomeController::class, 'ourService'])->name('our-service');
        Route::get('/about', [HomeController::class, 'about'])->name('about');
    }
);

// Route::middleware([])
//     ->group(function () {
//         Route::get('/', [HomeController::class, 'index'])->name('index');
//     });

// Route::get('/pusher', function () {
//     return view("admin::pusher");
// });

// Route::get('/pusher', function () {
//     return view("admin::pusher");
// });

// Route::get('booking', [BookingController::class, 'export_booking'])->name('booking');

// Route::get('customer-export', [CustomerController::class, 'export_customer'])->name('customer-export');

// Route::post('/upload', [HomeController::class, 'upload'])->name('image.upload');

// // Route::group([
// //     'prefix' => 'payway',
// // ], function () {
// //     Route::get('index', [PaywayController::class, 'index']);
// // });
// // Route::get('payway-form',[PaywayController::class,'payway_form']);
// // Route::get('payway-submit', [PaywayController::class, 'paymentSubmit']);