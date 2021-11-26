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
    Route::get('/part', [App\Http\Controllers\PpicController::class, 'getPart']);
    Route::get('/schedule/{status}', [App\Http\Controllers\PpicController::class, 'getEvent']);
    Route::get('/product', [App\Http\Controllers\PpicController::class, 'getProduk']);
    Route::get('/version/{id}', [App\Http\Controllers\PpicController::class, 'getVersionDetailProduk']);
    Route::get('/max-quantity/{id}', [App\Http\Controllers\PpicController::class, 'getMaxQuantity']);
    Route::post('/add-event', [App\Http\Controllers\PpicController::class, 'addEvent']);
    Route::post('/delete-event', [App\Http\Controllers\PpicController::class, 'deleteEvent']);
    Route::post('/update-event', [App\Http\Controllers\PpicController::class, 'updateConfirmation']);
    Route::get('/bppb/{status}', [App\Http\Controllers\PpicController::class, 'getBppb']);

    Route::get('/bppb/{id}', [App\Http\Controllers\PpicController::class, 'findSeriesBppb']);
    Route::get('/reset', [App\Http\Controllers\PpicController::class, 'resetConfirmation']);
    Route::get('/part-schedule/{id}', [App\Http\Controllers\PpicController::class, 'getPartFromSchedule']);
    // Route::prefix('/ppic')->group(function () {
    //     Route::get('part', [App\Http\Controllers\PpicController::class, 'getPart']);
    Route::get('/get-gbj-query', [App\Http\Controllers\PpicController::class, 'getGbjQuery']);
    Route::get('/get-gbj-datatable', [App\Http\Controllers\PpicController::class, 'getGbjDatatable']);
    Route::get('/jadwal', [App\Http\Controllers\PpicController::class, 'getJadwalPerakitan']);
    Route::get('test-event', [App\Http\Controllers\PpicController::class, 'testBroadcast']);
    Route::get('update-confirmation', [App\Http\Controllers\PpicController::class, 'updateConfirmation']);
});
Route::prefix('/provinsi')->group(function () {
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_provinsi']);
});
Route::prefix('/kota_kabupaten')->group(function () {
    Route::get('select', [App\Http\Controllers\ProvincesController::class, 'kota_kabupaten']);
});
Route::prefix('/customer')->group(function () {
    // Route::get('data/{filter}', [App\Http\Controllers\MasterController::class, 'get_data_customer']);
    Route::get('nama/{id}/{val}', [App\Http\Controllers\MasterController::class, 'get_nama_customer']);
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
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_produk']);
    Route::get('select/{id}', [App\Http\Controllers\MasterController::class, 'select_produk_id']);
});
Route::prefix('/penjualan_produk')->group(function () {
    Route::get('data/{value}', [App\Http\Controllers\MasterController::class, 'get_data_penjualan_produk']);
    // Route::post('create', [App\Http\Controllers\MasterController::class, 'create_penjualan_produk']);
    // Route::post('update/{id}', [App\Http\Controllers\MasterController::class, 'update_penjualan_produk']);
    Route::post('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_penjualan_produk']);
    Route::get('detail/{id}', [App\Http\Controllers\MasterController::class, 'get_data_detail_penjualan_produk']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_detail_penjualan_produk']);
    Route::get('update_modal/{id}', [App\Http\Controllers\MasterController::class, 'update_penjualan_produk_modal']);
    Route::get('check/{id}/{val}', [App\Http\Controllers\MasterController::class, 'check_penjualan_produk']);
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
    Route::get('data', [App\Http\Controllers\GudangController::class, 'get_data_barang_jadi']);
    Route::post('/create', [App\Http\Controllers\GudangController::class, 'StoreBarangJadi']);
    Route::post('/edit/{id}', [App\Http\Controllers\GudangController::class, 'UpdateBarangJadi']);
    Route::delete('/delete/{id}', [App\Http\Controllers\GudangController::class, 'DestroyBarangJadi']);
    Route::post('/get', [App\Http\Controllers\GudangController::class, 'GetBarangJadiByID']);

    Route::get('/test/{id}', [App\Http\Controllers\GudangController::class, 'test']);

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

Route::prefix('/draft')->group(function () {
    Route::post('/rancang', [\App\Http\Controllers\GudangController::class, 'storeDraftRancang']);
    Route::post('/final', [\App\Http\Controllers\GudangController::class, 'storeFinalRancang']);

    // get
    Route::post('/data', [\App\Http\Controllers\GudangController::class, 'getDraftPerakitan']);
});

Route::prefix('/transaksi')->group(function () {
    Route::get('/all', [\App\Http\Controllers\GudangController::class, 'getAllTransaksi']);
    Route::get('/history', [\App\Http\Controllers\GudangController::class, 'getHistorybyProduk']);
    Route::get('/history-detail/{id}', [\App\Http\Controllers\GudangController::class, 'getDetailHistory']);
    Route::get('/all-detail/{id}', [\App\Http\Controllers\GudangController::class, 'getDetailAll']);
});

Route::prefix('/dashboard-gbj')->group(function () {
    Route::get('/stok/1020', [\App\Http\Controllers\GudangController::class, 'getProdukstok1020']);
    Route::get('/stok/59', [\App\Http\Controllers\GudangController::class, 'getProdukstok59']);
    Route::get('/stok/14', [\App\Http\Controllers\GudangController::class, 'getProdukstok14']);

    Route::get('/in/36', [\App\Http\Controllers\GudangController::class, 'getProdukIn36']);
});

Route::prefix('/tfp')->group(function () {
    Route::post('/create', [\App\Http\Controllers\ProduksiController::class, 'CreateTFItem']);
    Route::post('/byso', [\App\Http\Controllers\ProduksiController::class, 'TfbySO']);

    // get
    Route::get('data', [\App\Http\Controllers\ProduksiController::class, 'getTFnon']);
    Route::get('noseri/{id}', [\App\Http\Controllers\ProduksiController::class, 'getNoseri']);
    Route::get('data-so', [\App\Http\Controllers\ProduksiController::class, 'getOutSO']);
    Route::get('detail-so/{id}', [\App\Http\Controllers\ProduksiController::class, 'getDetailSO']);
    Route::get('header-so/{id}', [\App\Http\Controllers\ProduksiController::class, 'headerSo']);
    Route::get('rakit', [\App\Http\Controllers\GudangController::class, 'getRakit']);
    Route::get('rakit-noseri/{id}', [\App\Http\Controllers\GudangController::class, 'getRakitNoseri']);
    Route::get('rakit-terima/{id}', [\App\Http\Controllers\GudangController::class, 'getTerimaRakit']);

    // check
    Route::post('/cekStok', [\App\Http\Controllers\ProduksiController::class, 'checkStok']);
});

Route::prefix('/spr')->group(function () {
    Route::get('/data', [App\Http\Controllers\SparepartController::class, 'get']);
    Route::post('/create', [App\Http\Controllers\SparepartController::class, 'store']);
    Route::post('/edit/{id}', [App\Http\Controllers\SparepartController::class, 'update']);
    Route::delete('/delete/{id}', [App\Http\Controllers\SparepartController::class, 'delete']);
    Route::get('/data/{id}', [App\Http\Controllers\SparepartController::class, 'getId']);
    Route::delete('/test', [App\Http\Controllers\SparepartController::class, 'deleteImage']);
});

Route::prefix('/noseri')->group(function () {
    Route::post('/edit/{id}', [App\Http\Controllers\NoseriController::class, 'UpdateNoSeri']);
    Route::delete('/delete/{id}', [App\Http\Controllers\NoseriController::class, 'DestroyNoSeri']);
});

Route::prefix('/ekatalog')->group(function () {
    // Route::get('data/{value}', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog']);
    Route::post('pengiriman/data', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog_pengiriman']);

    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_ekatalog']);
    Route::get('detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_ekatalog']);
    Route::get('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_ekatalog']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_ekatalog']);
    Route::get('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_ekatalog']);
});
Route::prefix('/spa')->group(function () {
    // Route::get('data', [App\Http\Controllers\PenjualanController::class, 'get_data_spa']);
    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spa']);
    Route::get('detail/{$id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spa']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_spa']);
    Route::get('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_spa']);
    Route::get('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_spa']);
});
Route::prefix('/spb')->group(function () {
    // Route::get('data', [App\Http\Controllers\PenjualanController::class, 'get_data_spb']);

    Route::get('detail/{$id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spb']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_spb']);
    Route::get('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_spb']);
    Route::get('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_spb']);
    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spb']);
});
Route::prefix('/qc')->group(function () {
    Route::get('dashboard/data/{value}', [App\Http\Controllers\QcController::class, 'dashboard_data']);
    Route::prefix('/so')->group(function () {
        // Route::post('create/{seri_id}/{tfgbj_id}/{pesanan_id}/{produk_id}', [App\Http\Controllers\QcController::class, 'create_data_qc']);
        Route::get('data/{value}', [App\Http\Controllers\QcController::class, 'get_data_so']);
        Route::get('seri/{value}/{tfgbj_id}', [App\Http\Controllers\QcController::class, 'get_data_seri_ekatalog']);
        Route::get('seri/select/{seri_id}/{produk_id}/{tfgbj_id}', [App\Http\Controllers\QcController::class, 'get_data_select_seri']);
        Route::get('data_test', [App\Http\Controllers\QcController::class, 'get_data_so_qc']);
        Route::get('detail/{id}', [App\Http\Controllers\QcController::class, 'get_data_detail_so']);
        Route::get('update_modal', [App\Http\Controllers\QcController::class, 'update_modal_so']);
        Route::prefix('/riwayat')->group(function () {
            Route::get('detail_modal/{id}', [App\Http\Controllers\QcController::class, 'detail_modal_riwayat_so']);
            Route::get('data', [App\Http\Controllers\QcController::class, 'get_data_riwayat_pengujian']);
            Route::get('select/{id}', [App\Http\Controllers\QcController::class, 'getProdukPesananSelect']);
            Route::get('detail/{id}', [App\Http\Controllers\QcController::class, 'get_data_detail_riwayat_pengujian']);
        });
        Route::prefix('/laporan')->group(function () {
            Route::post('/create', [App\Http\Controllers\QcController::class, 'laporan_outgoing']);
        });
    });
});

Route::group(['prefix' => '/ekspedisi'], function () {
    Route::get('data', [App\Http\Controllers\MasterController::class, 'get_data_ekspedisi']);
});
