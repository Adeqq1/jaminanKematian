<?php

use App\Http\Controllers\PengajuanKlaimController;
use App\Models\PengajuanKlaim;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [PengajuanKlaimController::class, 'dashboard'])->name('dashboard');
    Route::post('pengajuan-klaim', [PengajuanKlaimController::class, 'store'])->name('pengajuan-klaim.store');
    Route::get('pengajuan-klaim/{pengajuan}/pdf', [PengajuanKlaimController::class, 'pdf'])->name('pengajuan-klaim.pdf');
    Route::get('pengajuan-klaim/{pengajuan}/dokumen/{document}', [PengajuanKlaimController::class, 'document'])->name('pengajuan-klaim.document');
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('pengajuan', fn () => Inertia\Inertia::render('admin/Pengajuan', ['pengajuan' => PengajuanKlaim::with(['peserta', 'nomorAntrian'])->latest()->get()]))->name('pengajuan.index');
        Route::get('pengajuan/{pengajuan}', fn (PengajuanKlaim $pengajuan) => Inertia\Inertia::render('admin/PengajuanDetail', ['pengajuan' => $pengajuan->load(['peserta', 'nomorAntrian'])]))->name('pengajuan.show');
    });
});

require __DIR__.'/settings.php';
