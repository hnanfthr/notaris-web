<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan; // [PENTING] Tambahan biar bisa panggil Artisan
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuthController;

// --- 1. ROUTE PUBLIK (Bisa diakses Klien Tanpa Login) ---
// Ini jalur khusus buat scan QR Code
Route::get('/tracking/{uuid}', [ArchiveController::class, 'clientTracking'])->name('client.tracking');


// --- 2. ROUTE LOGIN (Tamu) ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- 3. ROUTE ADMIN/STAFF (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    
    Route::get('/', [ArchiveController::class, 'index'])->name('archives.index');
    
    // CRUD Arsip
    Route::get('/arsip/baru', [ArchiveController::class, 'create'])->name('archives.create');
    Route::post('/arsip', [ArchiveController::class, 'store'])->name('archives.store');
    
    Route::get('/arsip/digital', [ArchiveController::class, 'list'])->name('archives.list');
    Route::get('/arsip/detail/{uuid}', [ArchiveController::class, 'show'])->name('archives.show');
    
    Route::get('/arsip/{uuid}/edit', [ArchiveController::class, 'edit'])->name('archives.edit');
    Route::put('/arsip/{uuid}', [ArchiveController::class, 'update'])->name('archives.update');
    Route::delete('/arsip/{id}', [ArchiveController::class, 'destroy'])->name('archives.destroy');
    
    // Fitur-fitur Status & Tahapan
    Route::put('/arsip/{id}/complete', [ArchiveController::class, 'markAsComplete'])->name('archives.complete');
    
    // Route Restore (Kembalikan ke Dashboard)
    Route::put('/arsip/{id}/restore', [ArchiveController::class, 'restore'])->name('archives.restore');

    Route::put('/arsip/{id}/tahapan', [ArchiveController::class, 'updateTahapan'])->name('archives.updateTahapan');
    
    // Export Excel
    Route::get('/laporan/export', [ArchiveController::class, 'exportExcel'])->name('archives.export');
});

// --- 4. ROUTE DARURAT (PERBAIKAN SYMLINK 404) ---
// Akses ini sekali saja lewat browser: domain.com/generate-symlink
Route::get('/generate-symlink', function () {
    Artisan::call('storage:link');
    return 'Symlink berhasil dibuat! Silakan coba buka file PDF lagi.';
});