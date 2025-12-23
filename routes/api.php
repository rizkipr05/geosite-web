<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GeositeController;
use App\Http\Controllers\Api\MediaController;

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

Route::prefix('admin')->group(function () {
  Route::view('/login', 'admin.auth.login')->name('admin.login');

  Route::view('/', 'admin.dashboard')->name('admin.dashboard');
  Route::view('/categories', 'admin.categories')->name('admin.categories');
  Route::view('/geosites', 'admin.geosites')->name('admin.geosites');
  Route::view('/media', 'admin.media')->name('admin.media');
});


Route::middleware('admin_token')->group(function () {
  Route::get('/admin/me', [\App\Http\Controllers\Api\AdminAuthController::class, 'me']);
  Route::post('/admin/logout', [\App\Http\Controllers\Api\AdminAuthController::class, 'logout']);
});
