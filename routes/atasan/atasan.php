<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\atasan\BonBarangController;
use App\Http\Controllers\atasan\BarangController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| ATASAN AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['role:atasan', 'auth', 'user.active'])->prefix('atasan')->name('atasan.')->group(function () {

    Route::get('/dashboard', function () {
        return view('atasan.dashboard');
    })->name('dashboard');

    // BARANG
    Route::get('/barang', [BarangController::class, 'index'])
        ->name('barang.index');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // BON BARANG ATASAN
    Route::get('/bon-saya', [BonBarangController::class, 'myBonIndex'])
        ->name('bon.my');
    Route::get('/bon-saya/create', [BonBarangController::class, 'myBonCreate'])
        ->name('bon.my.create');
    Route::post('/bon-saya', [BonBarangController::class, 'myBonStore'])
        ->name('bon.my.store');
    Route::get('/bon-saya/{id}', [BonBarangController::class, 'myBonShow'])
        ->middleware('divisi')
        ->name('bon.my.show');
    Route::get('/bon-saya/{id}/edit', [BonBarangController::class, 'myBonEdit'])
        ->middleware('divisi')
        ->name('bon.my.edit');

    // BON BARANG APPROVAL
    Route::get('/bon', [BonBarangController::class, 'index'])
        ->name('bon.index');
    Route::get('/bon/{id}', [BonBarangController::class, 'show'])
        ->middleware('divisi')
        ->name('bon.show');
    Route::post('/bon/{id}/add-item', [BonBarangController::class, 'addItem'])
        ->middleware('divisi')
        ->name('bon.add-item');
    Route::post('/bon-item/{detailId}/delete', [BonBarangController::class, 'removeItem'])
        ->middleware('divisi')
        ->name('bon.remove-item');
    Route::post('/bon/{id}/approve', [BonBarangController::class, 'approve'])
        ->middleware('divisi')
        ->name('bon.approve');
    Route::post('/bon/{id}/reject', [BonBarangController::class, 'reject'])
        ->middleware('divisi')
        ->name('bon.reject');
});
