<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\gudang\BonBarangController;
use App\Http\Controllers\gudang\BonMasukController;
use App\Http\Controllers\gudang\BarangController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| GUDANG AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['role:gudang', 'auth', 'user.active', 'sanitize', 'rate.limit:60,1,gudang'])->prefix('gudang')->name('gudang.')->group(function () {

    Route::get('/dashboard', function () {
        return view('gudang.dashboard');
    })->name('dashboard');

    // BARANG
    Route::get('/barang', [BarangController::class, 'index'])
        ->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])
        ->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])
        ->name('barang.store');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])
        ->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])
        ->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])
        ->name('barang.destroy');

    // PROFILE
    Route::get('/components/profile', [ProfileController::class, 'index'])->name('profile');

    // BON BARANG
    Route::get('/bon', [BonBarangController::class, 'index'])
        ->name('bon.index');
    Route::get('/bon/{id}', [BonBarangController::class, 'show'])
        ->name('bon.show');
    Route::get('/bon-history', [BonBarangController::class, 'history'])
        ->name('bon.history');
        Route::get('/bon-history/{id}', [BonBarangController::class, 'showHistory'])
        ->name('bon.show-history');
    Route::post('/bon/{id}/approve', [BonBarangController::class, 'approve'])
        ->name('bon.approve');
    Route::post('/bon/{id}/reject', [BonBarangController::class, 'reject'])
        ->name('bon.reject');
    Route::post('/bon-delete-all', [BonBarangController::class, 'deleteAll'])
        ->name('bon.delete-all');
    Route::get('/bon/{id}/edit-detail/{detailId}', [BonBarangController::class, 'showEditDetail'])
        ->name('bon.show-edit-detail');
    Route::get('/bon/{id}/add-detail', [BonBarangController::class, 'showAddDetail'])
        ->name('bon.show-add-detail');
    Route::post('/bon/{id}/edit-detail', [BonBarangController::class, 'editDetail'])
        ->name('bon.edit-detail');
    Route::post('/bon/{id}/add-detail', [BonBarangController::class, 'addDetail'])
        ->name('bon.add-detail');
    Route::post('/bon/{id}/delete-detail', [BonBarangController::class, 'deleteDetail'])
        ->name('bon.delete-detail');

    // BON MASUK
    Route::get('/bon-masuk', [BonMasukController::class, 'index'])
        ->name('bon-masuk.index');
    Route::get('/bon-masuk/create', [BonMasukController::class, 'create'])
        ->name('bon-masuk.create');
    Route::post('/bon-masuk', [BonMasukController::class, 'store'])
        ->name('bon-masuk.store');
    Route::get('/bon-masuk/{id}', [BonMasukController::class, 'show'])
        ->name('bon-masuk.show');
});
