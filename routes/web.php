<?php

use Illuminate\Support\Facades\Route;

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
