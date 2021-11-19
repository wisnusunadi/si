<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/ppic')->group(function () {
    Route::get('/schedule/{status?}', [App\Http\Controllers\PpicController::class, 'getEvent']);
    Route::get('/product/{id?}', [App\Http\Controllers\PpicController::class, 'getProduk']);
    Route::post('/add-event', [App\Http\Controllers\PpicController::class, 'addEvent']);
    Route::post('/delete-event', [App\Http\Controllers\PpicController::class, 'deleteEvent']);
    Route::post('/update-event', [App\Http\Controllers\PpicController::class, 'updateEvent']);

    Route::get('/komentar', [App\Http\Controllers\PpicController::class, 'getKomentar']);
    Route::post('/add-komentar', [App\Http\Controllers\PpicController::class, 'addKomentar']);

    Route::get('/reset', [App\Http\Controllers\PpicController::class, 'resetEvent']);
    Route::get('/get-gbj-query', [App\Http\Controllers\PpicController::class, 'getGbjQuery']);
    Route::get('/get-gbj-datatable', [App\Http\Controllers\PpicController::class, 'getGbjDatatable']);
    Route::get('/broadcast', [App\Http\Controllers\PpicController::class, 'testBroadcast']);
});
Route::prefix('/provinsi')->group(function () {
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_provinsi']);
});
Route::prefix('/kota_kabupaten')->group(function () {
    Route::get('select', [App\Http\Controllers\ProvincesController::class, 'kota_kabupaten']);
});
Route::prefix('/customer')->group(function () {
    Route::get('data/{filter}', [App\Http\Controllers\MasterController::class, 'get_data_customer']);
    Route::post('detail/{id}', [App\Http\Controllers\MasterController::class, 'get_data_pesanan']);
    // Route::post('create', [App\Http\Controllers\MasterController::class, 'create_customer']);
    Route::get('update_modal/{id}', [App\Http\Controllers\MasterController::class, 'update_customer_modal']);
    //Route::put('update/{id}', [App\Http\Controllers\MasterController::class, 'update_customer']);
    Route::get('delete', [App\Http\Controllers\MasterController::class, 'delete_customer']);
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_customer']);;
    Route::get('select/{id}', [App\Http\Controllers\MasterController::class, 'select_customer_id']);;
    Route::get('check/{id}', [App\Http\Controllers\MasterController::class, 'check_customer']);
});

Route::prefix('/produk')->group(function () {
    Route::get('data', [App\Http\Controllers\MasterController::class, 'get_data_produk']);
    Route::post('create', [App\Http\Controllers\MasterController::class, 'create_produk']);
    Route::post('update', [App\Http\Controllers\MasterController::class, 'update_produk']);
    Route::delete('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_produk']);
    Route::get('check/{id}', [App\Http\Controllers\MasterController::class, 'check_produk']);
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_produk'])->name('sel.produk');
    Route::get('select/{id}', [App\Http\Controllers\MasterController::class, 'select_produk_id']);
    Route::get('select-layout', [App\Http\Controllers\MasterController::class, 'select_layout'])->name('sel.layout');
    Route::get('select-divisi', [App\Http\Controllers\MasterController::class, 'select_divisi'])->name('sel.divisi');
    Route::get('select-gbj', [App\Http\Controllers\MasterController::class, 'select_gbj'])->name('sel.gbj');
    Route::get('select-satuan', [App\Http\Controllers\MasterController::class, 'select_satuan'])->name('sel.satuan');
    Route::get('select-produk/{id}', [App\Http\Controllers\MasterController::class, 'select_produkId'])->name('sel.produkId');
    Route::get('search-produk', [App\Http\Controllers\MasterController::class, 'search_produk'])->name('src.produk');
});
Route::prefix('/penjualan_produk')->group(function () {
    Route::get('data/{value}', [App\Http\Controllers\MasterController::class, 'get_data_penjualan_produk']);
    // Route::post('create', [App\Http\Controllers\MasterController::class, 'create_penjualan_produk']);
    // Route::post('update/{id}', [App\Http\Controllers\MasterController::class, 'update_penjualan_produk']);
    Route::post('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_penjualan_produk']);
    Route::get('detail/{id}', [App\Http\Controllers\MasterController::class, 'get_data_detail_penjualan_produk']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_detail_penjualan_produk']);
    Route::get('update_modal/{id}', [App\Http\Controllers\MasterController::class, 'update_penjualan_produk_modal']);
    Route::get('check/{id}', [App\Http\Controllers\MasterController::class, 'check_penjualan_produk']);
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_penjualan_produk']);
    Route::get('select/{id}', [App\Http\Controllers\MasterController::class, 'select_penjualan_produk_id']);
});
Route::prefix('/penjualan')->group(function () {
    // Route::post('create', [App\Http\Controllers\PenjualanController::class, 'create_penjualan']);
    Route::get('chart', [App\Http\Controllers\PenjualanController::class, 'chart_penjualan']);
    Route::post('data', [App\Http\Controllers\PenjualanController::class, 'penjualan_data']);



    Route::prefix('/lacak')->group(function () {
        Route::get('data/{parameter}/{value}', [App\Http\Controllers\PenjualanController::class, 'get_lacak_penjualan']);
    });
});
Route::prefix('/so')->group(function () {
    Route::post('data', [App\Http\Controllers\PenjualanController::class, 'get_data_so']);
    // Route::post('create/{id}', [App\Http\Controllers\PenjualanController::class, 'create_so_ekatalog']);
});
Route::prefix('/laporan')->group(function () {
    Route::post('/create', [App\Http\Controllers\PenjualanController::class, 'laporan']);
    Route::get('/penjualan/{penjualan}/{distributor}/{tanggal_awal}/{tanggal_akhir}', [App\Http\Controllers\PenjualanController::class, 'get_data_laporan_penjualan']);
});
Route::prefix('/gbj')->group(function () {
    Route::get('data', [App\Http\Controllers\GudangController::class, 'get_data_barang_jadi'])->name('gbj.get');
    Route::post('/create', [App\Http\Controllers\GudangController::class, 'StoreBarangJadi'])->name('gbj.post');
    Route::post('/edit/{id}', [App\Http\Controllers\GudangController::class, 'UpdateBarangJadi']);
    Route::delete('/delete/{id}', [App\Http\Controllers\GudangController::class, 'DestroyBarangJadi']);
    Route::post('/get', [App\Http\Controllers\GudangController::class, 'GetBarangJadiByID']);

    // select
    Route::get('sel-product', [\App\Http\Controllers\GudangController::class, 'select_product']);
    Route::get('sel-product/{id}', [\App\Http\Controllers\GudangController::class, 'select_product_by_id']);
    Route::get('sel-satuan', [\App\Http\Controllers\GudangController::class, 'select_satuan']);
    Route::get('sel-layout', [\App\Http\Controllers\GudangController::class, 'select_layout']);
    Route::get('sel-divisi', [\App\Http\Controllers\GudangController::class, 'select_divisi']);
    Route::get('sel-gbj', [\App\Http\Controllers\GudangController::class, 'select_gbj']);

    // so
    Route::post('/createNon', [App\Http\Controllers\GudangController::class, 'tanpaSo']);

    // noseri
    Route::get('noseri/{id}', [\App\Http\Controllers\GudangController::class, 'getNoseri']);
    Route::get('history/{id}', [\App\Http\Controllers\GudangController::class, 'getHistory']);
    Route::post('noseri/{id}', [\App\Http\Controllers\GudangController::class, 'storeNoseri']);
});

Route::prefix('/tfp')->group(function () {
    Route::post('/create', [\App\Http\Controllers\ProduksiController::class, 'CreateTFItem']);

    // get
    Route::get('data', [\App\Http\Controllers\ProduksiController::class, 'getTFnon']);
    Route::get('noseri/{id}', [\App\Http\Controllers\ProduksiController::class, 'getNoseri']);
    Route::get('data-so', [\App\Http\Controllers\ProduksiController::class, 'getOutSO']);
    Route::get('detail-so/{id}', [\App\Http\Controllers\ProduksiController::class, 'getDetailSO']);
    Route::get('header-so/{id}', [\App\Http\Controllers\ProduksiController::class, 'headerSo']);

    // check
    Route::post('/cekStok', [\App\Http\Controllers\ProduksiController::class, 'checkStok']);
});

Route::prefix('/spr')->group(function () {
    Route::get('/data', [App\Http\Controllers\SparepartController::class, 'get']);
    Route::post('/create', [App\Http\Controllers\SparepartController::class, 'store']);
    Route::post('/edit/{id}', [App\Http\Controllers\SparepartController::class, 'update']);
    // Route::delete('/delete/{id}', [App\Http\Controllers\SparepartController::class, 'delete']);
    Route::get('/data/{id}', [App\Http\Controllers\SparepartController::class, 'getId']);
    Route::delete('/test', [App\Http\Controllers\SparepartController::class, 'deleteImage']);
});

Route::prefix('/noseri')->group(function () {
    Route::post('/edit/{id}', [App\Http\Controllers\NoseriController::class, 'UpdateNoSeri']);
    Route::delete('/delete/{id}', [App\Http\Controllers\NoseriController::class, 'DestroyNoSeri']);
});

Route::prefix('/ekatalog')->group(function () {
    Route::get('data/{value}', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog']);
    Route::post('pengiriman/data', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog_pengiriman']);
    Route::post('create', [App\Http\Controllers\PenjualanController::class, 'create_ekatalog']);
    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_ekatalog']);
    Route::get('detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_ekatalog']);
    Route::get('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_ekatalog']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_ekatalog']);
    Route::get('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_ekatalog']);
});
Route::prefix('/spa')->group(function () {
    Route::get('data', [App\Http\Controllers\PenjualanController::class, 'get_data_spa']);
    Route::get('create', [App\Http\Controllers\PenjualanController::class, 'create_spa']);
    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spa']);
    Route::get('detail/{$id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spa']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_spa']);
    Route::get('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_spa']);
    Route::get('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_spa']);
});
Route::prefix('/spb')->group(function () {
    Route::get('data', [App\Http\Controllers\PenjualanController::class, 'get_data_spb']);
    Route::get('create', [App\Http\Controllers\PenjualanController::class, 'create_spb']);
    Route::get('detail/{$id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spb']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_spb']);
    Route::get('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_spb']);
    Route::get('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_spb']);
    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spb']);
});
Route::prefix('/qc')->group(function () {
    Route::prefix('/so')->group(function () {
        Route::get('data/{value}', [App\Http\Controllers\QcController::class, 'get_data_so']);
        Route::get('update_modal', [App\Http\Controllers\QcController::class, 'update_modal_so']);
        Route::prefix('/riwayat')->group(function () {
            Route::get('detail_modal', [App\Http\Controllers\QcController::class, 'detail_modal_riwayat_so']);
        });
        Route::prefix('/laporan')->group(function () {
            Route::post('/create', [App\Http\Controllers\QcController::class, 'laporan_outgoing']);
        });
    });
});
