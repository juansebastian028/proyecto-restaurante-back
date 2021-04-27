<?php

use App\Http\Controllers\api\PassportAuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ShoppingCartController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\BranchController;
use App\Http\Controllers\api\CityController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\GroupModifierController;
use App\Http\Controllers\api\ModifierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login',[PassportAuthController::class, 'login']);
Route::post('/register',[PassportAuthController::class, 'register']);

Route::middleware(['auth:api', 'profile'])->group(function() {

    Route::middleware(['scope:super_admin'])
    ->group(function() {
        Route::resource('/users', UserController::class);
        Route::resource('/cities', CityController::class);
        Route::resource('/branches', BranchController::class);
        Route::resource('/orders', OrderController::class);
        Route::resource('/shopping-cart', ShoppingCartController::class);
        Route::resource('/products/categories', CategoryController::class);
        Route::resource('/products/group-modifiers', GroupModifierController::class);
        Route::resource('/products/modifiers', ModifierController::class);
        Route::resource('/products', ProductController::class);
    });

    Route::middleware(['scope:admin'])
    ->group(function() {
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::resource('/branches', BranchController::class);
        Route::resource('/orders', OrderController::class);
        Route::resource('/shopping-cart', ShoppingCartController::class);
        Route::get('/products', [ProductController::class, 'index']);
    });

    Route::middleware(['scope:e-commerce'])
    ->group(function() {
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::resource('/orders', OrderController::class);
        Route::resource('/shopping-cart', ShoppingCartController::class);
        Route::get('/products', [ProductController::class, 'index']);
    });
});






