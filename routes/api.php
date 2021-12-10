<?php

use App\Http\Controllers\MasterController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\SparepartController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/ppic')->group(function () {
    Route::get('/part', [App\Http\Controllers\PpicController::class, 'getPart']);
    Route::get('/product', [App\Http\Controllers\PpicController::class, 'getProduk']);
    Route::get('/version/{id}', [App\Http\Controllers\PpicController::class, 'getVersionDetailProduk']);
    Route::get('/max-quantity/{id}', [App\Http\Controllers\PpicController::class, 'getMaxQuantity']);
    Route::post('/delete-event', [App\Http\Controllers\PpicController::class, 'deleteEvent']);
    Route::post('/update-event', [App\Http\Controllers\PpicController::class, 'updateConfirmation']);
    Route::get('/bppb/{status}', [App\Http\Controllers\PpicController::class, 'getBppb']);

    Route::get('/bppb/{id}', [App\Http\Controllers\PpicController::class, 'findSeriesBppb']);
    Route::get('/reset', [App\Http\Controllers\PpicController::class, 'resetConfirmation']);
    Route::get('/part-schedule/{id}', [App\Http\Controllers\PpicController::class, 'getPartFromSchedule']);

    // new
    Route::get('/gbj/data', [App\Http\Controllers\PpicController::class, 'get_data_barang_jadi']);
    Route::get('/perakitan/data/{status?}', [App\Http\Controllers\PpicController::class, 'get_data_perakitan']);
    Route::get('/so/data', [App\Http\Controllers\PpicController::class, 'get_data_so']);
    Route::get('/schedule/{status?}', [App\Http\Controllers\PpicController::class, 'getEvent']);
    Route::post('/add-event', [App\Http\Controllers\PpicController::class, 'addEvent']);
    Route::post('/update-event/{id}', [App\Http\Controllers\PpicController::class, 'updateEvent']);
    Route::post('/update-many-event/{status}', [App\Http\Controllers\PpicController::class, 'updateManyEvent']);
    Route::post('/delete-event/{id}', [App\Http\Controllers\PpicController::class, 'deleteEvent']);
    // new

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
    Route::post('/cek', [App\Http\Controllers\GudangController::class, 'storeCekSO']);
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

    Route::get('/test', [App\Http\Controllers\GudangController::class, 'test']);

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
    Route::post('/data-seri', [\App\Http\Controllers\GudangController::class, 'getNoseriDraftRakit']);
});

Route::prefix('/transaksi')->group(function () {
    Route::get('/all', [\App\Http\Controllers\GudangController::class, 'getAllTransaksi']);
    Route::get('/history', [\App\Http\Controllers\GudangController::class, 'getHistorybyProduk']);
    Route::get('/history-detail/{id}', [\App\Http\Controllers\GudangController::class, 'getDetailHistory']);
    Route::get('/history-detail-seri/{id}', [\App\Http\Controllers\GudangController::class, 'getDetailHistorySeri']);
    Route::get('/all-detail/{id}', [\App\Http\Controllers\GudangController::class, 'getDetailAll']);
});

Route::prefix('/dashboard-gbj')->group(function () {
    Route::get('/noseri/{id}', [\App\Http\Controllers\GudangController::class, 'getNoseriTerima']);
    // produk
    Route::get('/stok/1020/h', [\App\Http\Controllers\GudangController::class, 'h1']);
    Route::get('/stok/59/h', [\App\Http\Controllers\GudangController::class, 'h2']);
    Route::get('/stok/14/h', [\App\Http\Controllers\GudangController::class, 'h3']);
    Route::get('/stok/1020', [\App\Http\Controllers\GudangController::class, 'getProdukstok1020']);
    Route::get('/stok/59', [\App\Http\Controllers\GudangController::class, 'getProdukstok59']);
    Route::get('/stok/14', [\App\Http\Controllers\GudangController::class, 'getProdukstok14']);

    Route::post('/in/36/h', [\App\Http\Controllers\GudangController::class, 'h4']);
    Route::post('/in/612/h', [\App\Http\Controllers\GudangController::class, 'h5']);
    Route::post('/in/1236/h', [\App\Http\Controllers\GudangController::class, 'h6']);
    Route::post('/in/36plus/h', [\App\Http\Controllers\GudangController::class, 'h7']);
    Route::post('/in/36', [\App\Http\Controllers\GudangController::class, 'getProdukIn36']);
    Route::post('/in/612', [\App\Http\Controllers\GudangController::class, 'getProdukIn612']);
    Route::post('/in/1236', [\App\Http\Controllers\GudangController::class, 'getProduk1236']);
    Route::post('/in/36plus', [\App\Http\Controllers\GudangController::class, 'getProduk36Plus']);
    Route::get('/byproduct', [\App\Http\Controllers\GudangController::class, 'getProdukByLayout']);
    Route::get('/test', [\App\Http\Controllers\GudangController::class, 'test']);

    // terima
    Route::post('/terimaproduk1/h', [\App\Http\Controllers\GudangController::class, 'hh1']);
    Route::post('/terimaproduk2/h', [\App\Http\Controllers\GudangController::class, 'hh2']);
    Route::post('/terimaproduk3/h', [\App\Http\Controllers\GudangController::class, 'hh3']);
    Route::post('/terimaproduk1', [\App\Http\Controllers\GudangController::class, 'getPenerimaanProduk1']);
    Route::post('/terimaproduk2', [\App\Http\Controllers\GudangController::class, 'getPenerimaanProduk2']);
    Route::post('/terimaproduk3', [\App\Http\Controllers\GudangController::class, 'getPenerimaanProduk3']);
    Route::post('/terimaall', [\App\Http\Controllers\GudangController::class, 'getPenerimaanAll']);

    // penjualan
    Route::post('/list1/h', [\App\Http\Controllers\GudangController::class, 'he1']);
    Route::post('/list2/h', [\App\Http\Controllers\GudangController::class, 'he2']);
    Route::post('/list/h', [\App\Http\Controllers\GudangController::class, 'he3']);
    Route::post('/list1', [\App\Http\Controllers\GudangController::class, 'list_tf1']);
    Route::post('/list2', [\App\Http\Controllers\GudangController::class, 'list_tf2']);
    Route::post('/list', [\App\Http\Controllers\GudangController::class, 'list_tf3']);
    Route::post('/list-all', [\App\Http\Controllers\GudangController::class, 'outSO']);
    Route::get('/list-detail/{id}', [\App\Http\Controllers\GudangController::class, 'detailsale']);
});

Route::prefix('/tfp')->group(function () {
    Route::post('/create', [\App\Http\Controllers\ProduksiController::class, 'CreateTFItem']);
    Route::post('/byso', [\App\Http\Controllers\ProduksiController::class, 'TfbySO']);
    Route::post('/create-noseri', [\App\Http\Controllers\GudangController::class, 'storeNoseri']);
    Route::post('/create-final', [\App\Http\Controllers\GudangController::class, 'finalDraftRakit']);

    // get
    Route::get('data', [\App\Http\Controllers\ProduksiController::class, 'getTFnon']);
    Route::get('noseri/{id}', [\App\Http\Controllers\ProduksiController::class, 'getNoseri']);
    Route::get('data-so', [\App\Http\Controllers\ProduksiController::class, 'getOutSO']);
    Route::get('detail-so/{id}/{value}', [\App\Http\Controllers\ProduksiController::class, 'getDetailSO']);
    Route::get('header-so/{id}/{value}', [\App\Http\Controllers\ProduksiController::class, 'headerSo']);
    Route::get('rakit', [\App\Http\Controllers\GudangController::class, 'getRakit']);
    Route::get('rakit-noseri/{id}', [\App\Http\Controllers\GudangController::class, 'getRakitNoseri']);
    Route::get('rakit-terima/{id}', [\App\Http\Controllers\GudangController::class, 'getTerimaRakit']);
    Route::post('/seri-so', [\App\Http\Controllers\ProduksiController::class, 'getNoseriSO']);

    // check
    Route::post('/cekStok', [\App\Http\Controllers\ProduksiController::class, 'checkStok']);
});

Route::prefix('/prd')->group(function () {
    Route::post('/minus10/h', [ProduksiController::class, 'h_minus10']);
    Route::post('/minus5/h', [ProduksiController::class, 'h_minus5']);
    Route::post('/exp/h', [ProduksiController::class, 'h_exp']);

    Route::post('/minus5', [ProduksiController::class, 'minus5']);
    Route::post('/minus10', [ProduksiController::class, 'minus10']);
    Route::post('/exp', [ProduksiController::class, 'expired']);

    Route::post('/exp_rakit/h', [ProduksiController::class, 'exp_rakit_h']);
    Route::post('/exp_rakit', [ProduksiController::class, 'exp_rakit']);

    Route::post('/exp_jadwal/h', [ProduksiController::class, 'exp_jadwal_h']);
    Route::post('/exp_jadwal', [ProduksiController::class, 'exp_jadwal']);

    // jadwal
    // plan
    Route::post('/plan', [ProduksiController::class, 'plan_rakit']);
    Route::post('/plan-cal', [ProduksiController::class, 'calender_plan']);

    // on
    Route::post('/ongoing', [ProduksiController::class, 'on_rakit']);
    Route::post('/ongoing-cal', [ProduksiController::class, 'calender_current']);
    Route::get('/ongoing/h/{id}', [ProduksiController::class, 'detailRakitHeader']);
    Route::get('/ajax_his_rakit', [ProduksiController::class, 'ajax_history_rakit']);
    Route::post('/rakit-seri', [ProduksiController::class, 'storeRakitNoseri']);

    Route::get('/testing', [ProduksiController::class, 'testing']);

    // kirim
    Route::get('/kirim', [ProduksiController::class, 'getSelesaiRakit']);
    Route::get('/headerSeri/{id}', [ProduksiController::class, 'getHeaderSeri']);
    Route::get('/historySeri/{id}/{value}', [ProduksiController::class, 'historySeri']);
    Route::get('/detailSeri1/{id}', [ProduksiController::class, 'detailSeri1']);
    Route::post('/send', [ProduksiController::class, 'kirimseri']);
    Route::post('/terimaseri', [ProduksiController::class, 'terimaseri']);

    // riwayat
    Route::prefix('/history')->group(function () {
        Route::post('/rakit/h', [ProduksiController::class, 'h_rakit']);
        Route::post('/unit/h', [ProduksiController::class, 'h_unit']);
        Route::get('/header/{id}', [ProduksiController::class, 'header_his_rakit']);
    });
});

Route::prefix('/spr')->group(function () {
    Route::get('/data', [App\Http\Controllers\SparepartController::class, 'get']);
    Route::post('/create', [App\Http\Controllers\SparepartController::class, 'store']);
    Route::post('/edit/{id}', [App\Http\Controllers\SparepartController::class, 'update']);
    Route::delete('/delete/{id}', [App\Http\Controllers\SparepartController::class, 'delete']);
    Route::get('/data/{id}', [App\Http\Controllers\SparepartController::class, 'getId']);
});

Route::prefix('/gk')->group(function () {
    Route::get('/his-spr/{id}', [SparepartController::class, 'history_spr']);
    Route::get('/unit', [SparepartController::class, 'get_unit']);
    Route::get('/his-unit/{id}', [SparepartController::class, 'history_unit']);

    Route::post('/draft-tf', [SparepartController::class, 'get_draft_tf']);
    Route::post('/draft-terima', [SparepartController::class, 'get_draft_terima']);
    Route::get('/noseri/{id}', [SparepartController::class, 'headerSeri']);
    Route::get('/coba', [SparepartController::class, 'coba']);
    Route::post('/detailseri', [SparepartController::class, 'get_detail_id1']);

    // select
    Route::post('/sel-spare', [MasterController::class, 'select_sparepart']);

    Route::post('/gkspr', [MasterController::class, 'select_gk_spr']);
    Route::post('/gkunit', [MasterController::class, 'select_gk_unit']);
    Route::post('/gklayout', [MasterController::class, 'select_gk_layout']);
    Route::get('/sel-tahun', [SparepartController::class, 'get_trx_tahun']);

    // store
    Route::post('/out-draft', [SparepartController::class, 'transfer_by_draft']);
    Route::post('/out-final', [SparepartController::class, 'transfer_by_final']);

    Route::post('/in-draft', [SparepartController::class, 'terima_by_draft']);
    Route::post('/in-final', [SparepartController::class, 'terima_by_final']);

    Route::post('/ubahunit', [SparepartController::class, 'updateUnit']);
    Route::post('/edit-in', [SparepartController::class, 'edit_draft_terima']);


    // history trx
    Route::prefix('/transaksi')->group(function () {
        Route::post('/by-product', [SparepartController::class, 'history_by_produk']);
        Route::post('/all', [SparepartController::class, 'historyAll']);
        Route::get('/noseri/{id}', [SparepartController::class, 'get_noseri_history']);
        Route::get('/header/{id}', [App\Http\Controllers\SparepartController::class, 'get_detail_id']);
        Route::get('/history/{id}', [App\Http\Controllers\SparepartController::class, 'get_trx']);
    });

    Route::prefix('/dashboard')->group(function () {
        Route::post('/tingkat', [SparepartController::class, 'byTingkat']);
        Route::post('/layout', [SparepartController::class, 'byLayout']);

        // stok
        Route::post('/stok/34', [SparepartController::class, 'stok34']);
        Route::post('/stok/510', [SparepartController::class, 'stok510']);
        Route::post('/stok/10', [SparepartController::class, 'stok10plus']);

        Route::post('/stok/34/h', [SparepartController::class, 'h_stok34']);
        Route::post('/stok/510/h', [SparepartController::class, 'h_stok510']);
        Route::post('/stok/10/h', [SparepartController::class, 'h_stok10plus']);

        // in
        Route::post('/in/36', [SparepartController::class, 'in36']);
        Route::post('/in/612', [SparepartController::class, 'in612']);
        Route::post('/in/1236', [SparepartController::class, 'in1236']);
        Route::post('/in/36plus', [SparepartController::class, 'in36plus']);

        Route::post('/in/36/h', [SparepartController::class, 'h_in36']);
        Route::post('/in/612/h', [SparepartController::class, 'h_in612']);
        Route::post('/in/1236/h', [SparepartController::class, 'h_in1236']);
        Route::post('/in/36plus/h', [SparepartController::class, 'h_in36plus']);
    });
});

Route::prefix('/noseri')->group(function () {
    Route::post('/edit/{id}', [App\Http\Controllers\NoseriController::class, 'UpdateNoSeri']);
    Route::delete('/delete/{id}', [App\Http\Controllers\NoseriController::class, 'DestroyNoSeri']);
});

Route::prefix('/ekatalog')->group(function () {
    Route::get('data/{value}', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog']);
    Route::post('pengiriman/data', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog_pengiriman']);

    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_ekatalog']);
    Route::get('detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_ekatalog']);
    Route::get('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_ekatalog']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_ekatalog']);
    Route::get('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_ekatalog']);
});
Route::prefix('/spa')->group(function () {
    Route::get('data', [App\Http\Controllers\PenjualanController::class, 'get_data_spa']);
    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spa']);
    Route::get('detail/{$id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spa']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_spa']);
    Route::get('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_spa']);
    Route::get('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_spa']);
});
Route::prefix('/spb')->group(function () {
    Route::get('data', [App\Http\Controllers\PenjualanController::class, 'get_data_spb']);

    Route::get('detail/{$id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spb']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_spb']);
    Route::get('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_spb']);
    Route::get('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_spb']);
    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spb']);
});
Route::prefix('/qc')->group(function () {
    Route::prefix('/so')->group(function () {
        Route::get('data/{value}', [App\Http\Controllers\QcController::class, 'get_data_so']);
        Route::get('seri/{value}', [App\Http\Controllers\QcController::class, 'get_data_seri_ekatalog']);
        Route::get('seri/select/{value}/{value2}', [App\Http\Controllers\QcController::class, 'get_data_select_seri']);
        Route::get('data_test', [App\Http\Controllers\QcController::class, 'get_data_so_qc']);
        Route::get('detail/{id}', [App\Http\Controllers\QcController::class, 'get_data_detail_so']);
        Route::get('update_modal', [App\Http\Controllers\QcController::class, 'update_modal_so']);
        Route::prefix('/riwayat')->group(function () {
            Route::get('detail_modal', [App\Http\Controllers\QcController::class, 'detail_modal_riwayat_so']);
        });
        Route::prefix('/laporan')->group(function () {
            Route::post('/create', [App\Http\Controllers\QcController::class, 'laporan_outgoing']);
        });
    });
});
