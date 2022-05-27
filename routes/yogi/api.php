<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\ProduksiController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v2')->group(function() {
    // produksi
    Route::prefix('/prd')->group(function() {
        Route::get('/produk-so', [ProduksiController::class, 'getCountProdukBySO']);
        Route::get('/data-so/{id}', [ProduksiController::class, 'detailCountProdukBySO']);
    });

    Route::prefix('/gbj')->group(function() {
        Route::get('template_noseri', [GudangController::class, 'download_template_noseri']);
        Route::post('import-noseri', [GudangController::class, 'import_noseri']);
        Route::post('store-importseri', [GudangController::class, 'import_noseri_to_db']);
        Route::post('delete-noseri', [GudangController::class, 'delete_noseri']);
    });
});
