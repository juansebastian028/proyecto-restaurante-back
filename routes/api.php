<?php

use App\Http\Controllers\api\BranchController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\CityController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/cities', CityController::class);

Route::resource('/categories', CategoryController::class);

Route::resource('/modifiers', ModifierController::class);

Route::resource('/branches', BranchController::class);
