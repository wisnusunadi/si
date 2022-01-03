<?php

use App\Http\Controllers\ProduksiController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v2')->group(function() {
    // produksi
    Route::prefix('/prd')->group(function() {
        Route::get('/produk-so', [ProduksiController::class, 'getCountProdukBySO']);
        Route::get('/data-so/{id}', [ProduksiController::class, 'detailCountProdukBySO']);
    });
});
