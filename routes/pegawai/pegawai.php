<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pegawai\BonBarangController;
use App\Http\Controllers\pegawai\BarangController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| PEGAWAI AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['role:pegawai', 'auth', 'user.active'])->prefix('pegawai')->name('pegawai.')->group(function () {

    Route::get('/dashboard', function () {
        return view('pegawai.dashboard');
    })->name('dashboard');

    // BARANG
    Route::get('/barang', [BarangController::class, 'index'])
        ->name('barang.index');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // BON BARANG
    Route::get('/bon', [BonBarangController::class, 'index'])
        ->name('bon.index');
    Route::get('/bon/create', [BonBarangController::class, 'create'])
        ->name('bon.create');
    Route::post('/bon', [BonBarangController::class, 'store'])
        ->name('bon.store');
    Route::get('/bon/{id}', [BonBarangController::class, 'show'])
        ->middleware('divisi')
        ->name('bon.show');
});
