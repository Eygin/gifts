<?php

use App\Http\Controllers\Api\GiftController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\GiftCategoryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
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

Route::post('/postDaftarAdmin', [UsersController::class, 'postDaftarAdmin']);
Route::post('/postDaftarUser', [UsersController::class, 'postDaftarUser']);
Route::post('/postLogin', [UsersController::class, 'postLogin']);
Route::post('/postLogout', [UsersController::class, 'postLogout']);

Route::middleware('auth:api')->prefix('gifts')->group(function () {
    Route::get('/', [GiftController::class, 'index']);
    Route::post('/', [GiftController::class, 'store']);
    Route::put('/{id?}', [GiftController::class, 'update']);
    Route::patch('/{id?}', [GiftController::class, 'update']);
    Route::get('/{id?}', [GiftController::class, 'show']);
    Route::delete('/{id?}', [GiftController::class, 'destroy']);
    Route::post('/{id?}/redeem', [GiftController::class, 'redeem']);
    Route::post('/{id?}/rating', [GiftController::class, 'rating']);
});

Route::middleware('auth:api')->prefix('category')->group(function () {
    Route::get('/', [GiftCategoryController::class, 'index']);
    Route::post('/', [GiftCategoryController::class, 'store']);
    Route::get('/{id?}', [GiftCategoryController::class, 'show']);
    Route::put('/{id?}', [GiftCategoryController::class, 'update']);
    Route::delete('/{id?}', [GiftCategoryController::class, 'destroy']);
});

Route::middleware('auth:api')->prefix('user')->group(function () {
    Route::get('/', [UsersController::class, 'index']);
    Route::post('/', [UsersController::class, 'store']);
    Route::get('/{id?}', [UsersController::class, 'show']);
    Route::put('/{id?}', [UsersController::class, 'update']);
    Route::delete('/{id?}', [UsersController::class, 'destroy']);
});

Route::middleware('auth:api')->prefix('role')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::get('/{id?}', [RoleController::class, 'show']);
    Route::put('/{id?}', [RoleController::class, 'update']);
    Route::delete('/{id?}', [RoleController::class, 'destroy']);
});

Route::middleware('auth:api')->prefix('permission')->group(function () {
    Route::get('/', [PermissionController::class, 'index']);
    Route::post('/', [PermissionController::class, 'store']);
    Route::get('/{id?}', [PermissionController::class, 'show']);
    Route::put('/{id?}', [PermissionController::class, 'update']);
    Route::delete('/{id?}', [PermissionController::class, 'destroy']);
});
