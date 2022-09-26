<?php

use App\Http\Controllers\AlatujiController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\KalibrasiPerbaikanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/inventory')->group(function() {
    Route::get('data', [AlatujiController::class, 'get_data_alatuji']);
    Route::post('store_alatuji', [AlatujiController::class, 'store_alatuji']);
    Route::get('peminjaman_hist/{id}/{role}/{uid}', [AlatujiController::class, 'peminjaman_hist']);//role bisa di ganti sama auth lain
    Route::get('perawatan_hist/{id}', [AlatujiController::class, 'perawatan_hist']);
    Route::get('peminjaman_terima_data/{id}', [AlatujiController::class, 'peminjaman_terima_data']);
    Route::get('data_dashboard_permintaan', [AlatujiController::class, 'get_data_dashboard_permintaan']);
    Route::get('data_dashboard_pengembalian', [AlatujiController::class, 'get_data_dashboard_pengembailan']);
});

Route::prefix('/verifikasi')->group(function(){
    Route::get('verifikasi_hist/{id}', [VerifikasiController::class, 'verifikasi_hist']);
});

Route::prefix('/kalibrasiperbaikan')->group(function(){
    Route::get('maintenance_hist/{id}/{x}', [KalibrasiPerbaikanController::class, 'maintenance_hist']);
    Route::get('/gambar_show/{id}/{jenis}/{tipe}', [KalibrasiPerbaikanController::class, 'gambar_show']);
    Route::get('/data_show/{id}/{jenis}', [KalibrasiPerbaikanController:: class, 'data_show']);
});

Route::prefix('test1ng')->group(function(){
    Route::get('storageLink', function(){return Storage::get('/public/img/memo.jpeg');});
});
