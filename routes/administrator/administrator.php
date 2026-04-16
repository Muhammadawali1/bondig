<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\administrator\AdministratorController;

/*
|--------------------------------------------------------------------------
| Administrator Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['role:administrator', 'auth', 'user.active'])->prefix('administrator')->name('administrator.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdministratorController::class, 'dashboard'])
        ->name('dashboard');

    // Division Management
    Route::prefix('divisions')->group(function () {
        Route::get('/', [AdministratorController::class, 'divisionsIndex'])
            ->name('divisions.index');
        Route::get('/create', [AdministratorController::class, 'divisionsCreate'])
            ->name('divisions.create');
        Route::post('/', [AdministratorController::class, 'divisionsStore'])
            ->name('divisions.store');
        Route::get('/{id}/edit', [AdministratorController::class, 'divisionsEdit'])
            ->name('divisions.edit');
        Route::put('/{id}', [AdministratorController::class, 'divisionsUpdate'])
            ->name('divisions.update');
        Route::delete('/{id}', [AdministratorController::class, 'divisionsDestroy'])
            ->name('divisions.destroy');
    });

    // Account Management
    Route::prefix('accounts')->group(function () {
        Route::get('/', [AdministratorController::class, 'accountsIndex'])
            ->name('accounts.index');
        Route::get('/create', [AdministratorController::class, 'accountsCreate'])
            ->name('accounts.create');
        Route::post('/', [AdministratorController::class, 'accountsStore'])
            ->name('accounts.store');
        Route::get('/{id}/edit', [AdministratorController::class, 'accountsEdit'])
            ->name('accounts.edit');
        Route::put('/{id}', [AdministratorController::class, 'accountsUpdate'])
            ->name('accounts.update');
        Route::delete('/{id}', [AdministratorController::class, 'accountsDestroy'])
            ->name('accounts.destroy');
    });

    // Password Change Requests
    Route::prefix('password-requests')->group(function () {
        Route::get('/', [AdministratorController::class, 'passwordRequestsIndex'])
            ->name('password-requests.index');
        Route::post('/{id}/approve', [AdministratorController::class, 'passwordRequestsApprove'])
            ->name('password-requests.approve');
        Route::put('/{id}/reject', [AdministratorController::class, 'passwordRequestsReject'])
            ->name('password-requests.reject');
    });
});
