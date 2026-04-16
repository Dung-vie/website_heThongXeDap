<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StationController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/stations', [StationController::class, 'index'])->name('station');
Route::get('/api/stations', [StationController::class, 'byWard']);           // AJAX theo ward
Route::get('/api/stations/{id}', [StationController::class, 'detail']);  // AJAX chi tiết

Route::middleware('auth')->group(function () {
    Route::get('/bike-rental',  [RentalController::class, 'rentForm'])->name('rental.rentForm');
    Route::post('/bike-rental', [RentalController::class, 'rent'])->name('rental.rent');

    Route::get('/bike-return',  [RentalController::class, 'returnForm'])->name('rental.returnForm');
    Route::post('/bike-return', [RentalController::class, 'returnBike'])->name('rental.return');

    Route::get('/review/{rental}',  [ReviewController::class, 'form'])->name('review.form');
    Route::post('/review/{rental}', [ReviewController::class, 'store'])->name('review.store');

    Route::get('/rental-history', [RentalController::class, 'history'])->name('rental.history');

    Route::get('/api/stations-for-rental',   [RentalController::class, 'stationsForRental']);
    Route::get('/api/bikes-in-station/{id}', [RentalController::class, 'bikesInStation']);
    Route::get('/api/stations-for-return',   [RentalController::class, 'stationsForReturn']);

});

Route::prefix('admin')->group(function() {
    Route::middleware('admin')->group(function() {
        // Route::get('/', fn() => redirect()->route('admin.index'));
        Route::get('/', function () {
            return view('admin.index');
        })->name('admin.index');
    });
});
