<?php

use App\Http\Controllers\AdminBikeController;
use App\Http\Controllers\AdminStationController;
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
Route::get('/stations/{id}', [StationController::class, 'showPage'])->name('stations.show');
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

Route::prefix('admin')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::middleware('admin')->group(function () {
        Route::get('/', fn() => redirect()->route('admin.bikes.index'));

        // Bin routes phải đặt TRƯỚC resource() ← quan trọng!
        Route::get('/bikes/bin',               [AdminBikeController::class, 'bin'])->name('admin.bikes.bin');
        Route::post('/bikes/restore-all',      [AdminBikeController::class, 'restoreAll'])->name('admin.bikes.restoreAll');
        Route::post('/bikes/delete-all',       [AdminBikeController::class, 'deleteAll'])->name('admin.bikes.deleteAll');
        Route::post('/bikes/restore-selected', [AdminBikeController::class, 'restoreSelected'])->name('admin.bikes.restoreSelected');
        Route::post('/bikes/delete-selected',  [AdminBikeController::class, 'deleteSelected'])->name('admin.bikes.deleteSelected');
        Route::post('/bikes/{id}/restore',     [AdminBikeController::class, 'restore'])->name('admin.bikes.restore');
        Route::delete('/bikes/{id}/force',     [AdminBikeController::class, 'forceDelete'])->name('admin.bikes.forceDelete');
        Route::resource('bikes', AdminBikeController::class)->names('admin.bikes');

        // Tương tự cho stations
        Route::get('/stations/bin',               [AdminStationController::class, 'bin'])->name('admin.stations.bin');
        Route::post('/stations/restore-all',      [AdminStationController::class, 'restoreAll'])->name('admin.stations.restoreAll');
        Route::post('/stations/delete-all',       [AdminStationController::class, 'deleteAll'])->name('admin.stations.deleteAll');
        Route::post('/stations/restore-selected', [AdminStationController::class, 'restoreSelected'])->name('admin.stations.restoreSelected');
        Route::post('/stations/delete-selected',  [AdminStationController::class, 'deleteSelected'])->name('admin.stations.deleteSelected');
        Route::post('/stations/{id}/restore',     [AdminStationController::class, 'restore'])->name('admin.stations.restore');
        Route::delete('/stations/{id}/force',     [AdminStationController::class, 'forceDelete'])->name('admin.stations.forceDelete');
        Route::resource('stations', AdminStationController::class)->names('admin.stations');
    });
});
