<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'role:admin,customer'])->group(function () {

Route::resource('categories','App\Http\Controllers\Admin\CategoryController')
 ->names([
        'index'   => 'admin.categories.index',
        'create'  => 'admin.categories.create',
        'store'   => 'admin.categories.store',
        'show'    => 'admin.categories.show',
        'edit'    => 'admin.categories.edit',
        'update'  => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

// Custom soft delete routes
    Route::get('trashed', [App\Http\Controllers\Admin\CategoryController::class, 'trashed'])->name('admin.categories.trashed');
    Route::post('{id}/restore', [App\Http\Controllers\Admin\CategoryController::class, 'restore'])->name('admin.categories.restore');
    Route::delete('{id}/force-delete', [App\Http\Controllers\Admin\CategoryController::class, 'forceDelete'])->name('admin.categories.forceDelete');


Route::resource('products','App\Http\Controllers\Admin\ProductsController')
->names([
        'index'   => 'admin.products.index',
        'create'  => 'admin.products.create',
        'store'   => 'admin.products.store',
        'show'    => 'admin.products.show',
        'edit'    => 'admin.products.edit',
        'update'  => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);
Route::resource('productVariants', 'App\Http\Controllers\Admin\ProductVariantController')
    ->names([
            'index' => 'admin.productVariants.index',
            'store' => 'admin.productVariants.store',
            'edit' => 'admin.productVariants.edit',
            'update' => 'admin.productVariants.update',
            'destroy' => 'admin.productVariants.destroy',
            'show' => 'admin.productVariants.show',
    ]);

//Custom route to view variants for a specific product
Route::get('products/{product}/variants', [App\Http\Controllers\Admin\ProductVariantController::class, 'indexByProduct'])
    ->name('admin.products.variants.index');
    Route::get('productVariants/create', [App\Http\Controllers\Admin\ProductVariantController::class, 'create'])
    ->name('admin.productVariants.create');

Route::resource('inventories', App\Http\Controllers\Admin\InventoryController::class)
->only(['index', 'edit', 'update']);

Route::resource('users','App\Http\Controllers\Admin\UserController');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

});

Auth::routes();

//Email verification routes


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
 
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');
 
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [App\Http\Controllers\CustomerController::class, 'index'])->name('customer.dashboard');
});