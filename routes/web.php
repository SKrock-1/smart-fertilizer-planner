<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FertilizerPlanController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LandParcelController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\LookupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoilTestController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::get('/locale/{locale}', LocaleController::class)->name('locale.switch');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/plans/{plan}/pdf', [FertilizerPlanController::class, 'downloadPdf'])
        ->name('plans.pdf');
});

Route::middleware(['auth', 'role:farmer'])->group(function () {
    Route::get('/api/parcel/{parcel}/details', [LookupController::class, 'parcelDetails'])->name('api.parcel.details');
    Route::get('/api/crop/{crop}/details', [LookupController::class, 'cropDetails'])->name('api.crop.details');
    Route::resource('parcels', LandParcelController::class);
    Route::resource('parcels.soil-tests', SoilTestController::class)
        ->only(['create', 'store', 'show', 'destroy']);
    Route::resource('plans', FertilizerPlanController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('crops', App\Http\Controllers\Admin\CropController::class);
        Route::resource('fertilizers', App\Http\Controllers\Admin\FertilizerController::class);
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    });

require __DIR__.'/auth.php';
