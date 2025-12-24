<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\GeositeController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::prefix('admin')->group(function () {
  Route::view('/login', 'admin.auth.login')->name('admin.login');

  Route::view('/', 'admin.dashboard')->name('admin.dashboard');
  Route::view('/categories', 'admin.categories')->name('admin.categories');
  Route::view('/geosites', 'admin.geosites')->name('admin.geosites');
  Route::view('/media', 'admin.media')->name('admin.media');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
Route::get('/explore/search', [ExploreController::class, 'index'])->name('explore.index'); // Alias for form submit if needed
Route::get('/geosites/{slug}', [GeositeController::class, 'show'])->name('geosites.show');
