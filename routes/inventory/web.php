<?php
use App\Http\Controllers\inventory\AlatujiController;
use App\Http\Controllers\inventory\PerawatanController;
use App\Http\Controllers\inventory\VerifikasiController;
use App\Http\Controllers\inventory\KalibrasiPerbaikanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Alat Uji
Route::group(['prefix' => 'alatuji', 'middleware' => 'auth'], function () {
    Route::get('/alatuji', function () {
        return view('page/lab/alatuji');
    })->name('alatuji');


    Route::get('/detail/{id}/{x?}', [AlatujiController::class, 'detail'])->name('detail');
    Route::get('/doc/{jenis}/{id}', [AlatujiController::class, 'show_document']);

    Route::post('/store_pinjam', [AlatujiController::class, 'store_pinjam']);

    Route::group(['middleware' => ['role']], function () {
        Route::get('/dashboard', [AlatujiController::class, 'dashboard'])->name('home');

        Route::get('/editalat/{id}', [AlatujiController::class, 'edit_alat']);
        Route::post('/editalat/store_editalat', [AlatujiController::class, 'store_editalat']);

        Route::post('/store_konfirmasi', [AlatujiController::class, 'store_konfirmasi']);
        Route::post('/store_kembali', [AlatujiController::class, 'store_kembali']);

        Route::get('/tambahalat', [AlatujiController::class, 'tambahalat']);
        Route::post('/store_jenisalat', [AlatujiController::class, 'store_jenisalat']);
        Route::post('/store_tambahbarang', [AlatujiController::class, 'store_tambahbarang']);
        Route::get('/tambahbarang', [AlatujiController::class, 'tambahbarang']);

        Route::get('/perawatan/{id}', [PerawatanController::class, 'index']);
        Route::post('/store_perawatan', [PerawatanController::class, 'store_perawatan']);

        Route::get('/verifikasi/{id}', [VerifikasiController::class, 'index']);

        //halaman tampilan formulir kalibrasi & perbaikan
        Route::get('/mt/{jenis}/{id}', [KalibrasiPerbaikanController::class, 'index']);

        Route::get('/konfirmasikalibrasi', function () {
            return view('kalibrasiperbaikanselesai', ['jenis' => 'Kalibrasi', 'data' => '0']);
        });

        Route::get('/konfirmasiperbaikan', function () {
            return view('kalibrasiperbaikanselesai', ['jenis' => 'Perbaikan', 'data' => '1']);
        });
        Route::post('/store_verifikasi', [VerifikasiController::class, 'store_verifikasi']);

        Route::post('/store_mt', [KalibrasiPerbaikanController::class, 'store_mt']);
        Route::post('/store_external', [KalibrasiPerbaikanController::class, 'store_external']);
        Route::get('/konfirmasi/{jenis}/{id}', [KalibrasiPerbaikanController::class, 'confirm']);
    });
});

// Alat Uji End
