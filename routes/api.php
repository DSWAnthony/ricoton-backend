<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TermsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\ProductController;


Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);

Route::post('/produc/{id}', [ProductController::class, 'update']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/setting', [SettingController::class, 'showSetting']);;
Route::post('/setting', [SettingController::class, 'store']);
Route::post('/settings', [SettingController::class, 'update']);

Route::get('/policy', [PolicyController::class, 'showPolicy']);
Route::post('/policy', [PolicyController::class, 'createPolicy']);
Route::put('/policy', [PolicyController::class, 'updatePolicy']);

Route::get('/terms', [TermsController::class, 'showTerm']);
Route::post('/terms', [TermsController::class, 'storeTerm']);
Route::put('/terms', [TermsController::class, 'updateTerm']);

Route::middleware('auth:api')->group(function () {
    Route::get('/admin',  function () {
        return 'prueba';
    });
    
});