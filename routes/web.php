<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\password\PasswordChangeController;

/*
|--------------------------------------------------------------------------
| Default Redirect
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Route Files
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth/auth.php';
require __DIR__.'/pegawai/pegawai.php';
require __DIR__.'/atasan/atasan.php';
require __DIR__.'/gudang/gudang.php';
require __DIR__.'/administrator/administrator.php';

/*
|--------------------------------------------------------------------------
| MAIN DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user.active', 'sanitize'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'atasan') {
            return redirect()->route('atasan.dashboard');
        } elseif ($user->role === 'pegawai') {
            return redirect()->route('pegawai.dashboard');
        } elseif ($user->role === 'gudang') {
            return redirect()->route('gudang.dashboard');
        } elseif ($user->role === 'administrator') {
            return redirect()->route('administrator.dashboard');
        }

        return redirect()->route('pegawai.dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| LEGACY DASHBOARD ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user.active'])->group(function () {
    Route::get('/dashboard/pegawai', fn () => redirect()->route('pegawai.dashboard'))
        ->name('dashboard.pegawai');

    Route::get('/dashboard/atasan', fn () => redirect()->route('atasan.dashboard'))
        ->name('dashboard.atasan');

    Route::get('/dashboard/gudang', fn () => redirect()->route('gudang.dashboard'))
        ->name('dashboard.gudang');
});

/*
|--------------------------------------------------------------------------
| GENERAL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user.active', 'sanitize', 'rate.limit:100,1,general'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/settings', fn () => 'Settings page - coming soon')->name('settings');
    
    // NOTIFIKASI
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/unread-count', [NotifikasiController::class, 'unreadCount'])->name('notifikasi.unread-count');
    Route::get('/notifikasi/recent', [NotifikasiController::class, 'recent'])->name('notifikasi.recent');
    Route::post('/notifikasi/{id}/mark-read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.mark-read');
    Route::post('/notifikasi/mark-all-read', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.mark-all-read');
    
    // PASSWORD CHANGE
    Route::get('/password', [PasswordChangeController::class, 'index'])->name('password.index');
    Route::get('/password/request', [PasswordChangeController::class, 'createRequest'])->name('password.request');
    Route::post('/password/request', [PasswordChangeController::class, 'storeRequest'])->name('password.store-request');
    Route::get('/password/change', [PasswordChangeController::class, 'directChange'])->name('password.direct-change');
    Route::post('/password/change', [PasswordChangeController::class, 'storeDirectChange'])->name('password.store-direct-change');
    Route::post('/password/{id}/approve', [PasswordChangeController::class, 'approve'])->name('password.approve');
    Route::post('/password/{id}/reject', [PasswordChangeController::class, 'reject'])->name('password.reject');
});
