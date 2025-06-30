<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TermsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\ProductController;


Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::get('/categories/{name}/products', [CategoryController::class, 'getProductsforCategory']);
Route::get('/categories/{id}/details', [CategoryController::class, 'getProductsForCategoryById']);

Route::post('/produc/{id}', [ProductController::class, 'update']);
Route::get('/logo', [SettingController::class, 'showSetting']);
Route::put('/logo', [SettingController::class, 'update']);
Route::post('/logo', [SettingController::class, 'store']);  
Route::delete('/logo', [SettingController::class, 'delete']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/about', [AboutController::class, 'showTerm']);
Route::post('/about', [AboutController::class, 'storeTerm']);
Route::put('/about', [AboutController::class, 'updateTerm']);



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/setting', [SettingController::class, 'showSetting']);;
Route::post('/setting', [SettingController::class, 'store']);
Route::post('/settings', [SettingController::class, 'update']);

Route::apiResource('coupons', CouponController::class);    
// Ruta para aplicar cupones
Route::post('/coupons/apply', [CouponController::class, 'apply']);

Route::get('/policy', [PolicyController::class, 'showPolicy']);
Route::post('/policy', [PolicyController::class, 'createPolicy']);
Route::put('/policy', [PolicyController::class, 'updatePolicy']);

Route::get('/terms', [TermsController::class, 'showTerm']);
Route::post('/terms', [TermsController::class, 'storeTerm']);
Route::put('/terms', [TermsController::class, 'updateTerm']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/admin',  function () {
        return 'prueba';
    });
    
});