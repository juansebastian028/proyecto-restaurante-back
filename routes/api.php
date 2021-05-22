<?php

use App\Http\Controllers\api\PassportAuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ShoppingCartController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\BranchController;
use App\Http\Controllers\api\CityController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\ModifierGroupController;
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
Route::get('/cities/{id}/products', [CityController::class,'getProductsByCity']);
Route::get('/cities/{city_id}/categories/{category_id}/products', [CityController::class,'getProductsByCategory']);


Route::middleware(['auth:api', 'profile'])->group(function() {

    Route::middleware(['scope:super_admin'])
    ->group(function() {
        Route::resource('/users', UserController::class);
        Route::resource('/cities', CityController::class);
        Route::resource('/products/categories', CategoryController::class);
        Route::resource('/products/group-modifiers', ModifierGroupController::class);
        Route::resource('/products/modifiers', ModifierController::class);
        Route::resource('/products', ProductController::class);
    });

    Route::middleware(['scope:super_admin,admin,e-commerce'])
    ->group(function() {
        Route::resource('/shopping-cart', ShoppingCartController::class);
        Route::get('/shopping-cart/user/{id}', [ShoppingCartController::class,'showByUser']);
    });
    
    Route::middleware(['scope:admin,e-commerce'])
    ->group(function() {
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
    });
    
    Route::middleware(['scope:super_admin,admin'])
    ->group(function() {
        Route::resource('/branches', BranchController::class);
        Route::get('/branches/{id}/products', [BranchController::class,'getProductsByBranch']);
    });

    Route::middleware(['scope:super_admin,admin,e-commerce'])
    ->group(function() {
        Route::resource('/orders', OrderController::class);
        Route::get('/orders/products/{id}', [OrderController::class, 'showProductsOrder']);
        Route::get('/orders/user/{id}', [OrderController::class, 'showByUser']);
        Route::get('/orders/branch/{id}', [OrderController::class, 'showByBranch']);
        Route::put('/orders/cancelOrder/{id}', [OrderController::class, 'cancelOrder']);
        Route::put('/orders/finalizeOrder/{id}', [OrderController::class, 'finalizeOrder']);
    });
});

Route::get('/cities', [CityController::class, 'index']);
Route::get('/products/categories', [CategoryController::class, 'index']);









