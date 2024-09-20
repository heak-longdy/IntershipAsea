<?php

use App\Services\FileManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin as Admin;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\WalletHistoryController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UomController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\BarberController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CustomerPointController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PlacementTypeController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\SectorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Auth

function CRUD($Controller, $routeName)
{
    Route::group([
        'prefix' => $routeName,
        'as'     => $routeName . '-'
    ], function () use ($Controller) {
        Route::get('list/{status?}', [$Controller, 'index'])->name('list');
        Route::get('create', [$Controller, 'onCreate'])->name('create');
        Route::get('edit/{id?}', [$Controller, 'onEdit'])->name('edit');
        Route::post('save/{id?}', [$Controller, 'Save'])->name('save');
        Route::match(['get', 'post'], 'status/{id}/{status}', [$Controller, 'updateStatus'])->name('status');
        Route::post('delete/{id?}', [$Controller, 'delete'])->name('delete');
        Route::post('restore/{id?}', [$Controller, 'restore'])->name('restore');
        Route::post('destroy/{id?}', [$Controller, 'destroy'])->name('destroy');

        Route::get('change-password/{id?}', [$Controller, 'onChangePassword'])->name('change-password');
        Route::post('save-password/{id?}', [$Controller, 'onSavePassword'])->name('save-password');

        Route::get('permission/{id?}', [$Controller, 'onPermission'])->name('permission');
        Route::post('save-permission/{id?}', [$Controller, 'onSavePermission'])->name('save-permission');
    });
}


Route::prefix('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin-login');
    });
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::get('/forgot', [UserController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('/login/post', [AuthController::class, 'login'])->name('login-post');
    Route::get('/sign-out', [AuthController::class, 'signOut'])->name('sign-out');
});

Route::middleware(['AdminGuard'])
    ->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin-dashboard');
        });

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        //Position
        // Route::group([
        //     'prefix' => 'position',
        //     'as'     => 'position-'
        // ], function () {
        //     Route::get('list/{status?}', [PositionController::class, 'index'])->name('list');
        //     Route::get('create', [PositionController::class, 'onCreate'])->name('create');
        //     Route::get('edit/{id?}', [PositionController::class, 'onEdit'])->name('edit');
        //     Route::post('save/{id?}', [PositionController::class, 'onSave'])->name('save');
        //     Route::match(['get', 'post'], 'status/{id}/{status}', [PositionController::class, 'onUpdateStatus'])->name('status');
        // });
        

        //Report
        Route::group([
            'prefix' => 'report',
            'as'     => 'report-'
        ], function () {
            Route::get('barber/{status?}', [ReportController::class, 'barberReport'])->name('barber');
            Route::get('shop/{status?}', [ReportController::class, 'shopReport'])->name('shop');
        });

        

        //Wallet
        Route::group([
            'prefix' => 'wallet',
            'as'     => 'wallet-'
        ], function () {
            Route::get('list/{status?}', [WalletHistoryController::class, 'index'])->name('list');
            Route::match(['get', 'post'], 'status/{id}/{status}', [WalletHistoryController::class, 'onUpdateStatus'])->name('status');
        });

        //Bookings
        Route::group([
            'prefix' => 'booking',
            'as'     => 'booking-'
        ], function () {
            Route::get('list/{status?}', [BookingController::class, 'index'])->name('list');
            Route::match(['get', 'post'], 'status/{id}/{status}', [WalletHistoryController::class, 'onUpdateStatus'])->name('status');
            Route::get('list-product/{status?}', [BookingController::class, 'product'])->name('list-product');
            Route::get('edit/{id?}', [BookingController::class, 'edit'])->name('edit');
            Route::post('save', [BookingController::class, 'save'])->name('save');
            Route::get('delete/{id}', [BookingController::class, 'delete'])->name('delete');
            Route::get('restore/{id}', [BookingController::class, 'Restore'])->name('restore');
            Route::get('destroy/{id}', [BookingController::class, 'Destroy'])->name('destroy');

            Route::get('report', [BookingController::class, 'report'])->name('report');
        });


        //Service
        Route::group([
            'prefix' => 'service',
            'as'     => 'service-'
        ], function () {
            Route::get('list/{status?}', [ServiceController::class, 'index'])->name('list');
            Route::get('create/{id?}', [ServiceController::class, 'onCreate'])->name('create');
            Route::post('save/{id?}', [ServiceController::class, 'onSave'])->name('save');
            Route::match(['get', 'post'], 'status/{id}/{status}', [ServiceController::class, 'onUpdateStatus'])->name('status');
        });

        // setting
        // Category
        Route::group([
            'prefix' => 'category',
            'as'     => 'category-'
        ], function () {
            Route::get('list/{status?}', [CategoryController::class, 'index'])->name('list');
            Route::get('data', [CategoryController::class, 'data'])->name('data');
            Route::get('create', [CategoryController::class, 'onCreate'])->name('create');
            Route::get('edit/{id?}', [CategoryController::class, 'onEdit'])->name('edit');
            Route::post('save/{id?}', [CategoryController::class, 'onSave'])->name('save');
            Route::match(['get', 'post'], 'status/{id}/{status}', [CategoryController::class, 'onUpdateStatus'])->name('status');
        });


        // Discount
        Route::group([
            'prefix' => 'discount',
            'as'     => 'discount-'
        ], function () {
            Route::get('list/{status?}', [DiscountController::class, 'index'])->name('list');

            Route::get('create', [DiscountController::class, 'onCreate'])->name('create');
            Route::get('edit/{id?}', [DiscountController::class, 'onEdit'])->name('edit');
            // Route::post('save/{id?}', [UomController::class, 'onSave'])->name('save');
            //Route::match(['get', 'post'], 'status/{id}/{status}', [UomController::class, 'onUpdateStatus'])->name('status');
        });

        CRUD(PartnerController::class,'partner');
        CRUD(SectorController::class,'sector');
        CRUD(PositionController::class,'position');
        CRUD(UserController::class,'user');

        CRUD(JobController::class,'job');
        CRUD(BlogController::class,'blog');

        CRUD(PlacementTypeController::class,'placement-type');


        //Select
        Route::group([
            'prefix' => 'select',
            'as'     => 'select-'
        ], function () {

            Route::get('product', [Admin\SelectController::class, 'selectProduct'])->name('product');
            Route::get('customer', [Admin\SelectController::class, 'selectCustomer'])->name('customer');
            Route::get('supplier', [Admin\SelectController::class, 'selectSupplier'])->name('supplier');

            Route::get('stock-product', [Admin\SelectController::class, 'stockSelectProduct'])->name('stock-product');
            Route::get('stock-shop', [Admin\SelectController::class, 'stockSelectShop'])->name('stock-shop');
            Route::get('shopNotIn', [Admin\SelectController::class, 'stockSelectShopNotInID'])->name('shopNotIn');

            //shopProduct
            Route::get('shop-product', [Admin\SelectController::class, 'selectShopProduct'])->name('shop-product');

            //findProductStock
            Route::get('find-shop-product', [Admin\SelectController::class, 'findShopProduct'])->name('find-shop-product');

            //selectBarber
            Route::get('barber', [Admin\SelectController::class, 'SelectBarber'])->name('barber');

            //selectShop
            Route::get('shop', [Admin\SelectController::class, 'SelectShop'])->name('shop');

            //product
            Route::get('type-product', [Admin\SelectController::class, 'SelectProductSearch'])->name('product');
            //service
            Route::get('type-service', [Admin\SelectController::class, 'SelectServiceSearch'])->name('service');

            //In
            Route::get('shop-in-product', [Admin\SelectController::class, 'productInShop'])->name('shop-in-product');

            //Position
            Route::get('position', [Admin\SelectController::class, 'SelectPositionSearch'])->name('position');
            //Sector
            Route::get('sector', [Admin\SelectController::class, 'SelectSectorSearch'])->name('sector');

            //placement type
            Route::get('placement-type', [Admin\SelectController::class, 'SelectPlacementTypeSearch'])->name('placement-type');

        });


        //Setting
        Route::group([
            'prefix' => 'setting',
            'as' => 'setting-',
        ], function () {
            Route::get('/data', [SettingController::class, 'index'])->name('setting');
            Route::post('save/{id?}', [SettingController::class, 'store'])->name('save');
        });

        //File Manager
        Route::prefix('file-manager')
            ->name('file-manager-')
            ->group(function () {
                Route::get('/index', [FileManager::class, 'index'])->name('index');
                Route::get('/first', [FileManager::class, 'first'])->name('first');
                Route::get('/files', [FileManager::class, 'getFiles'])->name('files');
                Route::get('/folders', [FileManager::class, 'getFolders'])->name('folders');
                Route::post('/upload', [FileManager::class, 'uploadFile'])->name('upload');
                Route::post('/rename-file', [FileManager::class, 'renameFile'])->name('rename-file');
                Route::delete('/delete-file', [FileManager::class, 'deleteFile'])->name('delete-file');

                //folder
                Route::post('/create-folder', [FileManager::class, 'createFolder'])->name('create-folder');
                Route::post('/rename-folder', [FileManager::class, 'renameFolder'])->name('rename-folder');
                Route::delete('/delete-folder', [FileManager::class, 'deleteFolder'])->name('delete-folder');

                //trash bin
                Route::delete('/delete-all', [FileManager::class, 'deleteAll'])->name('delete-all');
                Route::put('/restore-all', [FileManager::class, 'restoreAll'])->name('restore-all');
            });

        // Page
        Route::group([
            'prefix' => 'page',
            'as' => 'page-',
        ], function () {
            Route::get('/{type?}', [Admin\PageController::class, 'page'])->name('page');
            Route::post('save/{id?}', [Admin\PageController::class, 'onSave'])->name('save');
        });

        //Contact
        Route::group([
            'prefix' => 'contact',
            'as' => 'contact-',
        ], function () {
            Route::get('/{type?}', [Admin\ContactController::class, 'index'])->name('contact');
            Route::post('save/{id?}', [Admin\ContactController::class, 'store'])->name('save');
        });

        //setting
        Route::group([
            'prefix' => 'currency',
            'as'     => 'currency-'
        ], function () {
            Route::get('list/{id}', [Admin\CurrencyController::class, 'index'])->name('list');
            Route::get('create/{id?}', [Admin\CurrencyController::class, 'onCreate'])->name('create');
            Route::post('save/{id?}', [Admin\CurrencyController::class, 'onSave'])->name('save');
            Route::match(['get', 'post'], 'status/{id}/{status}', [Admin\CurrencyController::class, 'onUpdateStatus'])->name('status');
            Route::get('delete/{id}', [Admin\CurrencyController::class, 'delete'])->name('delete');
            Route::get('restore/{id}', [Admin\CurrencyController::class, 'restore'])->name('restore');
            Route::get('destroy/{id}', [Admin\CurrencyController::class, 'destroy'])->name('destroy');
        });
    });

Route::get('clear-cache', function () {
    Artisan::call('optimize:clear');
    return "Cache is cleared";
});
