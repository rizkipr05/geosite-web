<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\GeositeController;
use App\Http\Controllers\Api\Admin\MediaController;
use App\Http\Controllers\Api\Admin\ImportController;

    // Route::get('/test', function () {
    //     return response()->json([
    //         'message' => 'API Laravel 12 OK'
    //     ]);
    // });


Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
Route::get('/admin/me', [AdminAuthController::class, 'me']);

Route::middleware('admin_token')->group(function () {
  Route::get('/admin/me', [AdminAuthController::class, 'me']);
  Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
});

Route::middleware(['admin_token'])->prefix('admin')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('geosites', GeositeController::class);

    // media khusus per geosite
    Route::get('geosites/{geosite}/media', [MediaController::class, 'index']);
    Route::post('geosites/{geosite}/media', [MediaController::class, 'store']);
    Route::delete('media/{media}', [MediaController::class, 'destroy']);

    // publish/unpublish
    Route::patch('geosites/{geosite}/status', [GeositeController::class, 'toggleStatus']);
});


Route::middleware('admin_token')->group(function () {
  Route::get('/admin/me', [\App\Http\Controllers\Api\AdminAuthController::class, 'me']);
  Route::post('/admin/logout', [\App\Http\Controllers\Api\AdminAuthController::class, 'logout']);
});


Route::middleware(['admin_token'])->prefix('admin')->group(function () {
    Route::post('/import/probolinggo/tourism', [ImportController::class, 'tourismProbolinggo']);
});