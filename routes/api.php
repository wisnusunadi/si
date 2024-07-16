<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PpicController;
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

Route::post('/login', [App\Http\Controllers\ApiController::class, 'authenticate']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json([
        'id' => $request->user()->id,
        'username' => $request->user()->karyawan->nama,
        'divisi_id' =>  $request->user()->divisi_id,
        'foto' => $request->user()->foto,
    ]);
});

Route::prefix('/pinjaminta')->group(function () {
    Route::post('/kirim', [App\Http\Controllers\GudangController::class, 'pinjaminta_kirim'])->middleware('jwt.verify');
    Route::get('/selectitem', [App\Http\Controllers\GudangController::class, 'pinjaminta_selectitem'])->middleware('jwt.verify');
    Route::get('/show', [App\Http\Controllers\GudangController::class, 'pinjaminta_show'])->middleware('jwt.verify');
    Route::get('/detail/{id}', [App\Http\Controllers\GudangController::class, 'pinjaminta_detail'])->middleware('jwt.verify');
    Route::post('/update', [App\Http\Controllers\GudangController::class, 'pinjaminta_update'])->middleware('jwt.verify');
    Route::post('/update_status', [App\Http\Controllers\GudangController::class, 'pinjaminta_update_status'])->middleware('jwt.verify');

    Route::prefix('/atasan')->group(function () {
        Route::get('/show', [App\Http\Controllers\GudangController::class, 'pinjaminta_atasan_show'])->middleware('jwt.verify');
        Route::post('/update_status', [App\Http\Controllers\GudangController::class, 'pinjaminta_update_status'])->middleware('jwt.verify');
    });
});

Route::prefix('/master')->group(function () {
    Route::prefix('/buka_periode')->group(function () {
        Route::post('/permintaan', [App\Http\Controllers\MasterController::class, 'permintaan_periode'])->middleware('jwt.verify');
        Route::get('/selesai/{id}', [App\Http\Controllers\MasterController::class, 'reset_periode'])->middleware('jwt.verify');
        Route::get('/show', [App\Http\Controllers\MasterController::class, 'show_periode']);
        Route::post('/update/{id}', [App\Http\Controllers\MasterController::class, 'update_periode']);
    });

    Route::post('/produk/no_akd', [App\Http\Controllers\MasterController::class, 'check_no_akd']);
    Route::put('/produk/update_coo/{id}', [App\Http\Controllers\MasterController::class, 'update_coo_master_produk'])->name('master.produk.update_coo');
    Route::put('/produk/update_kodelab/{id}', [App\Http\Controllers\MasterController::class, 'update_kode_lab']);
});

Route::prefix('/ppic')->group(function () {
    Route::prefix('/jadwal_rework')->group(function () {
        Route::prefix('/perencanaan')->group(function () {
            Route::post('/', [App\Http\Controllers\PpicController::class, 'create_data_perakitan_rework_perencanaan']);
            Route::get('/', [App\Http\Controllers\PpicController::class, 'show_perencanaan_rework']);
            Route::get('/{id}', [App\Http\Controllers\PpicController::class, 'edit_ppic_rework']);
            Route::put('/', [App\Http\Controllers\PpicController::class, 'update_ppic_rework']);
            Route::post('/delete', [App\Http\Controllers\PpicController::class, 'delete_ppic_rework']);
        });

        Route::prefix('/pelaksanaan')->group(function () {
            Route::post('/', [App\Http\Controllers\PpicController::class, 'create_data_perakitan_rework_pelaksanaan']);
            Route::get('/', [App\Http\Controllers\PpicController::class, 'show_pelaksanaan_rework']);
            Route::get('/{id}', [App\Http\Controllers\PpicController::class, 'edit_ppic_rework']);
            Route::put('/', [App\Http\Controllers\PpicController::class, 'update_ppic_rework']);
            Route::post('/delete', [App\Http\Controllers\PpicController::class, 'delete_ppic_rework']);
        });
    });

    Route::post('/update_pwd', [App\Http\Controllers\Auth\ResetPasswordController::class, 'updatePwd'])->middleware('jwt.verify');
    Route::post('/master_stok/data', [App\Http\Controllers\PpicController::class, 'get_master_stok_data'])->middleware('jwt.verify');
    Route::post('/master_stok/detail/{id}', [App\Http\Controllers\PpicController::class, 'get_detail_master_stok'])->middleware('jwt.verify');
    Route::post('/master_pengiriman/data', [App\Http\Controllers\PpicController::class, 'get_master_pengiriman_data'])->middleware('jwt.verify');
    Route::post('/master_pengiriman/detail/{id}', [App\Http\Controllers\PpicController::class, 'get_detail_master_pengiriman'])->middleware('jwt.verify');
    Route::get('/data/perakitan/{status?}', [App\Http\Controllers\PpicController::class, 'get_data_perakitan']);
    Route::get('/datatables/perakitan', [App\Http\Controllers\PpicController::class, 'get_datatables_data_perakitan']);
    Route::get('/datatables/perakitandetail/{id}', [App\Http\Controllers\PpicController::class, 'get_datatables_data_perakitan_detail'])->middleware('jwt.verify');
    Route::get('/data/rencana_perakitan', [App\Http\Controllers\PpicController::class, 'get_data_perakitan_rencana'])->middleware('jwt.verify');
    Route::get('/data/gbj', [App\Http\Controllers\PpicController::class, 'get_data_barang_jadi']);
    Route::get('/data/produkidgbj', [App\Http\Controllers\PpicController::class, 'get_data_produk_id_gbj']);
    Route::get('/data/so', [App\Http\Controllers\PpicController::class, 'get_data_so']);
    Route::get('/data/so2', [PpicController::class, 'get_data_so2']);
    Route::get('/data/so/detail/{id}', [App\Http\Controllers\PpicController::class, 'get_data_so_detail']);
    Route::get('/data/master_pengiriman_for_ppic/detail/{id}', [App\Http\Controllers\PpicController::class, 'get_detail_pengiriman_for_ppic']);
    Route::get('/data/gk/sparepart', [App\Http\Controllers\PpicController::class, 'get_data_sparepart_gk'])->middleware('jwt.verify');
    Route::get('/data/gk/unit', [App\Http\Controllers\PpicController::class, 'get_data_unit_gk']);
    Route::get('/data/komentar', [App\Http\Controllers\PpicController::class, 'get_komentar_jadwal_perakitan']);
    Route::post('/create/perakitan', [App\Http\Controllers\PpicController::class, 'create_data_perakitan']);
    Route::post('/update/perakitan/{id}', [App\Http\Controllers\PpicController::class, 'update_data_perakitan']);
    Route::post('/update/perakitans/{status}', [App\Http\Controllers\PpicController::class, 'update_many_data_perakitan']);
    Route::post('/delete/perakitan/{id}', [App\Http\Controllers\PpicController::class, 'delete_data_perakitan']);
    Route::get('/counting/status/perakitan', [App\Http\Controllers\PpicController::class, 'counting_status_data_perakitan']);
    Route::get('/counting/komentar', [App\Http\Controllers\PpicController::class, 'count_proses_jadwal']);
    Route::post('/create/komentar', [App\Http\Controllers\PpicController::class, 'create_komentar_jadwal_perakitan']);
    Route::post('/update/komentar', [App\Http\Controllers\PpicController::class, 'update_komentar_jadwal_perakitan']);
    Route::post('/send_notification', [App\Http\Controllers\PpicController::class, 'send_notification']);
    Route::get('/data/produk_so/{id}/{value}', [PpicController::class, 'get_data_pesanan_produk']);
    Route::get('/test/query', [App\Http\Controllers\PpicController::class, 'test_query']);
});

Route::prefix('/provinsi')->group(function () {
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_provinsi']);
    Route::get('select_edit', [App\Http\Controllers\MasterController::class, 'select_provinsi_edit']);
});
Route::prefix('/kota_kabupaten')->group(function () {
    Route::get('select', [App\Http\Controllers\ProvincesController::class, 'kota_kabupaten']);
});
Route::prefix('/customer')->group(function () {
    Route::post('data/{divisi_id}/{filter}', [App\Http\Controllers\MasterController::class, 'get_data_customer'])->middleware('jwt.verify');
    Route::get('nama/{id}/{val}', [App\Http\Controllers\MasterController::class, 'get_nama_customer']);
    Route::post('detail/{id}', [App\Http\Controllers\MasterController::class, 'get_data_pesanan']);
    Route::get('get_instansi/{id}/{year}', [App\Http\Controllers\MasterController::class, 'get_instansi_customer']);
    // Route::post('create', [App\Http\Controllers\MasterController::class, 'create_customer']);
    Route::get('update_modal/{id}', [App\Http\Controllers\MasterController::class, 'update_customer_modal']);
    //Route::put('update/{id}', [App\Http\Controllers\MasterController::class, 'update_customer']);
    Route::delete('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_customer']);
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_customer']);
    Route::get('select_rencana', [App\Http\Controllers\MasterController::class, 'select_customer_rencana']);

    Route::get('select/{id}', [App\Http\Controllers\MasterController::class, 'select_customer_id']);;
    Route::get('check/{id}', [App\Http\Controllers\MasterController::class, 'check_customer']);
});

Route::prefix('/produk')->group(function () {
    Route::prefix('/rw')->group(function () {
        Route::get('/select', [App\Http\Controllers\MasterController::class, 'select_parent_rw']);
        Route::get('/select/{id}', [App\Http\Controllers\MasterController::class, 'select_item_rw']);
    });
    Route::get('data', [App\Http\Controllers\MasterController::class, 'get_data_produk']);
    Route::get('variasi', [App\Http\Controllers\MasterController::class, 'get_data_produk_variasi']);
    Route::post('create', [App\Http\Controllers\MasterController::class, 'create_produk']);
    Route::post('update', [App\Http\Controllers\MasterController::class, 'update_produk']);
    Route::delete('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_produk']);
    Route::get('check/{id}', [App\Http\Controllers\MasterController::class, 'check_produk']);
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_produk']);
    Route::get('select/{id}', [App\Http\Controllers\MasterController::class, 'select_produk_id']);
});
Route::prefix('/penjualan_produk')->group(function () {
    Route::post('data/{value}/{min}/{max}', [App\Http\Controllers\MasterController::class, 'get_data_penjualan_produk']);
    // Route::post('create', [App\Http\Controllers\MasterController::class, 'create_penjualan_produk']);
    // Route::post('update/{id}', [App\Http\Controllers\MasterController::class, 'update_penjualan_produk']);
    Route::delete('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_penjualan_produk']);
    Route::post('detail/{id}', [App\Http\Controllers\MasterController::class, 'get_data_detail_penjualan_produk']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_detail_penjualan_produk']);
    Route::get('update_modal/{id}', [App\Http\Controllers\MasterController::class, 'update_penjualan_produk_modal']);
    Route::get('check/{id}/{val}', [App\Http\Controllers\MasterController::class, 'check_penjualan_produk']);
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_penjualan_produk']);
    Route::get('select/{id}', [App\Http\Controllers\MasterController::class, 'select_penjualan_produk_id']);
    Route::get('select_param/{penjualan}', [App\Http\Controllers\MasterController::class, 'select_penjualan_produk_param']);
    Route::get('select_param_new/{penjualan}', [App\Http\Controllers\MasterController::class, 'select_penjualan_produk_param_new']);
});

Route::prefix('/penj')->group(function () {
    Route::prefix('/detail')->group(function () {
        Route::get('/edit/{id}', [App\Http\Controllers\PenjualanControllerNew::class, 'penjualanDetailEdit']);
    });
    Route::prefix('/action')->group(function () {
        Route::post('/', [App\Http\Controllers\PenjualanControllerNew::class, 'penjualanStore']);
        Route::post('/edit', [App\Http\Controllers\PenjualanControllerNew::class, 'penjualanStoreEdit']);
    });
});


Route::prefix('/penjualan')->group(function () {
    // Route::post('create', [App\Http\Controllers\PenjualanController::class, 'create_penjualan']);
    Route::get('/getYearsPeriode', [App\Http\Controllers\PenjualanController::class, 'getYearsPeriodePenjualan']);
    Route::get('/items/{id}', [App\Http\Controllers\PenjualanController::class, 'get_items_penjualan']);
    Route::post('/penjualan/data/{jenis}/{status}/{tahun}', [App\Http\Controllers\PenjualanController::class, 'penjualan_data']);
    Route::get('/ekatalog_data/{akn}', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog_emindo']);
    Route::get('/laporan', [App\Http\Controllers\PenjualanController::class, 'get_laporans']);
    Route::get('/spa_data/{po}', [App\Http\Controllers\PenjualanController::class, 'get_data_spa_emindo']);
    Route::get('check_ekatalog/{akn}', [App\Http\Controllers\PenjualanController::class, 'cek_paket']);
    Route::get('penjualan_emindo', [App\Http\Controllers\PenjualanController::class, 'penjualan_data_emindo']);
    Route::get('check_po/{po}', [App\Http\Controllers\PenjualanController::class, 'cek_po']);
    Route::get('chart', [App\Http\Controllers\PenjualanController::class, 'chart_penjualan']);
    // Rout    Route::post('/penjualan/cancel/{id}/{jenis}', [App\Http\Controllers\PenjualanController::class, 'cancel_spa_spb']);
    Route::post('data', [App\Http\Controllers\PenjualanController::class, 'penjualan_data']);
    Route::post('check_no_paket/{id}/{val}', [App\Http\Controllers\PenjualanController::class, 'check_no_paket']);
    Route::post('check_no_urut/{id}/{val}', [App\Http\Controllers\PenjualanController::class, 'check_no_urut']);
    Route::get('check_alamat', [App\Http\Controllers\PenjualanController::class, 'check_alamat']);
    //   Route::get('customer/data/{filter}', [App\Http\Controllers\MasterController::class, 'get_data_customer']);
    Route::prefix('/rencana')->group(function () {
        Route::post('produk/{customer_id}/{instansi}/{year}', [App\Http\Controllers\PenjualanController::class, 'get_data_rencana_produk']);
    });

    Route::prefix('/pesanan')->group(function () {
        Route::put('update/{id}/{jenis}', [App\Http\Controllers\PenjualanController::class, 'update_penjualan_pesanan']);
        Route::post('produk/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_paket_pesanan_ekat']);
        Route::post('produk/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_pesanan_detail']);
    });

    Route::prefix('/retur_po')->group(function () {
        Route::post('/kirim/', [App\Http\Controllers\PenjualanController::class, 'kirim_prd_retur_po']);
        Route::get('/detail_paket/{id}', [App\Http\Controllers\PenjualanController::class, 'get_detail_paket_retur_po']);
        Route::get('/detail_prd/{id}', [App\Http\Controllers\PenjualanController::class, 'get_detail_prd_retur_po']);
        Route::post('/cek_noretur/', [App\Http\Controllers\PenjualanController::class, 'cek_noretur']);
    });

    Route::prefix('/batal_po/{divisi}/')->group(function () {
        Route::get('/show', [App\Http\Controllers\PenjualanController::class, 'batal_po_show_divisi']);
        Route::get('/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'detail_batal_po_divisi']);
        Route::get('/seri/{id}', [App\Http\Controllers\PenjualanController::class, 'seri_batal_po_divisi']);
        Route::post('/kirim/', [App\Http\Controllers\PenjualanController::class, 'kirim_batal_po_divisi']);
        Route::post('/kirim_semua/', [App\Http\Controllers\PenjualanController::class, 'kirim_batal_po_divisi_semua']);
    });

    Route::prefix('/batal_po')->group(function () {
        Route::get('/detail_paket/{id}', [App\Http\Controllers\PenjualanController::class, 'get_detail_paket_batal_po']);
        Route::get('/detail_prd/{id}', [App\Http\Controllers\PenjualanController::class, 'get_detail_prd_batal_po']);
        Route::post('/kirim/', [App\Http\Controllers\PenjualanController::class, 'kirim_prd_batal_po']);
    });

    Route::prefix('/lacak')->group(function () {
        Route::post('/data/{parameter}/{value}', [App\Http\Controllers\PenjualanController::class, 'get_lacak_penjualan']);
    });

    Route::group(['prefix' => '/rencana'], function () {
        Route::delete('delete/{id}', [App\Http\Controllers\RencanaPenjualanController::class, 'delete_detail_rencana']);
        Route::get('select_tahun', [App\Http\Controllers\RencanaPenjualanController::class, 'select_tahun_rencana']);
        Route::post('show/{customer}/{tahun}', [App\Http\Controllers\RencanaPenjualanController::class, 'get_data_rencana']);

        Route::post('real/show/{id}', [App\Http\Controllers\RencanaPenjualanController::class, 'get_show_data_real']);
        Route::post('real/data/{id}', [App\Http\Controllers\RencanaPenjualanController::class, 'get_data_real']);
        Route::post('realisasi/update/{id}', [App\Http\Controllers\RencanaPenjualanController::class, 'get_update_realisasi']);
    });
});

Route::prefix('/so')->group(function () {
    Route::post('data', [App\Http\Controllers\PenjualanController::class, 'get_data_so']);
    Route::post('/cek', [App\Http\Controllers\GudangController::class, 'storeCekSO'])->middleware('jwt.verify');
});

Route::prefix('/laporan')->group(function () {
    Route::post('/create', [App\Http\Controllers\PenjualanController::class, 'laporan']);
    Route::post('/penjualan/{penjualan}/{distributor}/{tanggal_awal}/{tanggal_akhir}', [App\Http\Controllers\PenjualanController::class, 'get_data_laporan_penjualan']);
    Route::post('/qc_2/{jenis}/{produk}/{no_so}/{hasil}/{tgl_awal}/{tgl_akhir}', [App\Http\Controllers\QcController::class, 'get_data_laporan_qc_2']);
    Route::post('/qc/detail/{id}', [App\Http\Controllers\QcController::class, 'get_data_detail_laporan_qc']);

    Route::post('/qc/{jenis}/{produk}/{no_so}/{hasil}/{tgl_awal}/{tgl_akhir}', [App\Http\Controllers\QcController::class, 'get_data_laporan_qc']);
    Route::post('/logistik/{pengiriman}/{ekspedisi}/{tgl_awal}/{tgl_akhir}', [App\Http\Controllers\LogistikController::class, 'get_data_laporan_logistik']);
});

Route::prefix('/gbj')->group(function () {
    Route::get('/riwayat_ganti_unit', [App\Http\Controllers\GudangController::class, 'riwayat_ganti_unit']);
    Route::get('/riwayat_ganti_unit/{id}', [App\Http\Controllers\GudangController::class, 'riwayat_ganti_unit_produk']);
    Route::prefix('/batal_po')->group(function () {
        Route::get('/show', [App\Http\Controllers\GudangController::class, 'get_batal_po']);
        Route::get('/detail/{id}', [App\Http\Controllers\GudangController::class, 'get_detail_batal_po']);
        Route::get('/seri/{id}', [App\Http\Controllers\GudangController::class, 'get_detail_seri_batal_po']);
        Route::post('/kirim/', [App\Http\Controllers\GudangController::class, 'kirim_seri_batal_po']);
    });
    Route::prefix('/pinjaminta')->group(function () {
        Route::get('/show', [App\Http\Controllers\GudangController::class, 'pinjaminta_show_gbj'])->middleware('jwt.verify');
        Route::post('/update', [App\Http\Controllers\GudangController::class, 'pinjaminta_update_status'])->middleware('jwt.verify');
    });

    Route::prefix('/ganti_unit')->group(function () {
        Route::post('/', [App\Http\Controllers\GudangController::class, 'tf_ganti_unit_data']);
        Route::get('/', [App\Http\Controllers\GudangController::class, 'ganti_unit_data']);
        Route::get('/{id}', [App\Http\Controllers\GudangController::class, 'ganti_unit_data_detail']);
        Route::get('/seri/{id}', [App\Http\Controllers\GudangController::class, 'ganti_unit_data_detail_seri']);
    });

    Route::prefix('/rw')->group(function () {
        Route::get('/surat_pengiriman/{id}', [GudangController::class, 'surat_pengiriman']);
        Route::get('/surat_penyerahan/{id}', [GudangController::class, 'surat_penyerahan_rw']);
        Route::get('/riwayat_permintaan', [GudangController::class, 'riwayat_rw_permintaan']);
        Route::get('/riwayat_permintaan/{id}', [GudangController::class, 'riwayat_rw_permintaan_detail']);
        Route::get('/belum_kirim', [GudangController::class, 'belum_kirim_rw']);
        Route::post('/belum_kirim', [GudangController::class, 'kirim_permintaan'])->middleware('jwt.verify');
        Route::post('/belum_kirim/produk/', [GudangController::class, 'belum_kirim_rw_produk']);
        Route::get('/belum_kirim/seri/{id}', [GudangController::class, 'belum_kirim_rw_seri']);
        Route::get('/dp/seri/', [GudangController::class, 'terima_perakitan_rw']);
        Route::post('/terima', [GudangController::class, 'store_perakitan_rw'])->middleware('jwt.verify');
        Route::get('/dp/seri/{id}', [GudangController::class, 'terima_perakitan_detail_rw']);
        Route::get('/riwayat_penerimaan', [GudangController::class, 'riwayat_rw_penerimaan']);
    });


    Route::post('update_stok', [App\Http\Controllers\GudangController::class, 'updateStokGudang']);
    Route::post('data', [App\Http\Controllers\GudangController::class, 'get_data_barang_jadi']);
    Route::post('/create', [App\Http\Controllers\GudangController::class, 'StoreBarangJadi']);
    Route::post('/edit/{id}', [App\Http\Controllers\GudangController::class, 'UpdateBarangJadi']);
    Route::delete('/delete/{id}', [App\Http\Controllers\GudangController::class, 'DestroyBarangJadi']);
    Route::post('/get', [App\Http\Controllers\GudangController::class, 'GetBarangJadiByID']);
    Route::post('data-so', [GudangController::class, 'getSODone']);
    Route::get('modal_data_seri_tf/{id}', [App\Http\Controllers\GudangController::class, 'history_modal_data_seri_tf']);
    Route::get('/modal_data/{id}', [App\Http\Controllers\GudangController::class, 'history_modal_gbj']);
    Route::get('/modal_data_non/{id}', [App\Http\Controllers\GudangController::class, 'history_modal_gbj_non']);
    Route::get('/modal_data_seri/{id}', [App\Http\Controllers\GudangController::class, 'history_modal_gbj_seri']);
    Route::get('/modal_data_seri_non/{id}', [App\Http\Controllers\GudangController::class, 'history_modal_gbj_seri_non']);
    Route::get('/test', [App\Http\Controllers\GudangController::class, 'test']);
    // select
    Route::get('sel-product', [GudangController::class, 'select_product']);
    Route::get('sel-product/{id}', [GudangController::class, 'select_product_by_id']);
    Route::get('sel-satuan', [GudangController::class, 'select_satuan']);
    Route::get('sel-layout', [GudangController::class, 'select_layout']);
    Route::get('sel-divisi', [GudangController::class, 'select_divisi'])->middleware('jwt.verify');
    Route::get('sel-gbj', [GudangController::class, 'select_gbj'])->middleware('jwt.verify');
    Route::get('sel-var', [ProduksiController::class, 'select_variasi']);

    // so
    Route::post('/createNon', [App\Http\Controllers\GudangController::class, 'tanpaSo']);

    // noseri
    Route::get('noseri/{id}', [GudangController::class, 'getNoseri']);
    Route::post('noseri-done/{id}', [GudangController::class, 'getNoseriDone']);
    Route::get('history/{id}', [GudangController::class, 'getHistory']);
    Route::post('noseri/{id}', [GudangController::class, 'storeNoseri']);
    Route::post('ceknoseri', [GudangController::class, 'ceknoseri']);
    Route::post('ubahseri', [GudangController::class, 'updateSeriLayout']);
    Route::post('addSeri', [GudangController::class, 'addSeri']);
    Route::post('readyseri', [GudangController::class, 'cekReadySeri` ']);
});

Route::prefix('/draft')->group(function () {
    Route::post('/rancang', [GudangController::class, 'storeDraftRancang']);
    Route::post('/final', [GudangController::class, 'storeFinalRancang']);

    // get
    Route::post('/data', [GudangController::class, 'getDraftPerakitan']);
    Route::post('/data-seri', [GudangController::class, 'getNoseriDraftRakit']);
});

Route::prefix('/transaksi')->group(function () {
    Route::get('/all', [GudangController::class, 'getAllTransaksi']);
    Route::get('/history', [GudangController::class, 'getHistorybyProduk']);
    Route::get('/history-detail/{id}', [GudangController::class, 'getDetailHistory']);
    Route::get('/history-detail-seri/{id}', [GudangController::class, 'getDetailHistorySeri']);
    Route::get('/all-detail/{id}/{date}', [GudangController::class, 'getDetailAll']);
});

Route::prefix('/dashboard-gbj')->group(function () {
    Route::get('/noseri/{id}', [GudangController::class, 'getNoseriTerima'])->middleware('jwt.verify');
    // produk
    Route::get('/stok/1020/h', [GudangController::class, 'h1']);
    Route::get('/stok/59/h', [GudangController::class, 'h2']);
    Route::get('/stok/14/h', [GudangController::class, 'h3']);
    Route::get('/stok/1020', [GudangController::class, 'getProdukstok1020'])->middleware('jwt.verify');
    Route::get('/stok/59', [GudangController::class, 'getProdukstok59'])->middleware('jwt.verify');
    Route::get('/stok/14', [GudangController::class, 'getProdukstok14'])->middleware('jwt.verify');
    Route::post('/in/36/h', [GudangController::class, 'h4']);
    Route::post('/in/612/h', [GudangController::class, 'h5']);
    Route::post('/in/1236/h', [GudangController::class, 'h6']);
    Route::post('/in/36plus/h', [GudangController::class, 'h7']);
    Route::post('/in/36', [GudangController::class, 'getProdukIn36'])->middleware('jwt.verify');
    Route::post('/in/612', [GudangController::class, 'getProdukIn612'])->middleware('jwt.verify');
    Route::post('/in/1236', [GudangController::class, 'getProduk1236'])->middleware('jwt.verify');
    Route::post('/in/36plus', [GudangController::class, 'getProduk36Plus'])->middleware('jwt.verify');
    Route::get('/byproduct', [GudangController::class, 'getProdukByLayout'])->middleware('jwt.verify');
    Route::get('/test', [GudangController::class, 'test']);

    // terima
    Route::post('/terimaproduk1/h', [GudangController::class, 'hh1']);
    Route::post('/terimaproduk2/h', [GudangController::class, 'hh2']);
    Route::post('/terimaproduk3/h', [GudangController::class, 'hh3']);
    Route::post('/terimaproduk1', [GudangController::class, 'getPenerimaanProduk1'])->middleware('jwt.verify');
    Route::post('/terimaproduk2', [GudangController::class, 'getPenerimaanProduk2'])->middleware('jwt.verify');
    Route::post('/terimaproduk3', [GudangController::class, 'getPenerimaanProduk3'])->middleware('jwt.verify');
    Route::post('/terimaall', [GudangController::class, 'getPenerimaanAll'])->middleware('jwt.verify');

    // penjualan
    Route::post('/list1/h', [GudangController::class, 'he1']);
    Route::post('/list2/h', [GudangController::class, 'he2']);
    Route::post('/list/h', [GudangController::class, 'he3']);
    Route::post('/list1', [GudangController::class, 'list_tf1'])->middleware('jwt.verify');
    Route::post('/list2', [GudangController::class, 'list_tf2'])->middleware('jwt.verify');
    Route::post('/list', [GudangController::class, 'list_tf3'])->middleware('jwt.verify');
    Route::post('/list-all', [GudangController::class, 'outSO'])->middleware('jwt.verify');
    Route::get('/list-detail/{id}/{value}', [GudangController::class, 'detailsale']);
});

Route::prefix('/tfp')->group(function () {
    Route::post('/create', [ProduksiController::class, 'CreateTFItem']);
    Route::post('/byso', [ProduksiController::class, 'TfbySO']);
    Route::post('/byso-batal/{id}', [GudangController::class, 'TfbySOBatal']);
    Route::post('/byso-final', [GudangController::class, 'TfbySOFinal'])->middleware('jwt.verify');
    Route::post('/create-noseri', [GudangController::class, 'storeNoseri']);
    Route::post('/create-final', [GudangController::class, 'finalDraftRakit']);

    Route::post('/updateRancangSO', [ProduksiController::class, 'updateRancangSO']);
    Route::post('/updateFinalSO', [ProduksiController::class, 'updateFinalSO']);

    // get
    Route::get('data', [ProduksiController::class, 'getTFnon']);
    Route::post('noseri', [ProduksiController::class, 'getNoseri']);
    Route::get('data-so', [ProduksiController::class, 'getOutSO']);
    Route::get('sudah-dicek', [ProduksiController::class, 'getSOCek']);
    Route::get('belum-dicek/{value}', [ProduksiController::class, 'getSOCekBelum']);
    Route::get('detail-transfer-so/{id}', [ProduksiController::class, 'getDetailTransferSO']);
    Route::get('detail-so/{id}', [ProduksiController::class, 'getDetailSO']);
    Route::get('edit-so/{id}/{value}', [ProduksiController::class, 'getEditSO']);
    Route::get('header-so/{id}/{value}', [ProduksiController::class, 'headerSo']);
    Route::get('rakit', [GudangController::class, 'getRakit']);
    Route::get('rakit-noseri/{id}/{value}', [GudangController::class, 'getRakitNoseri']);
    Route::get('rakit-terima/{id}/{value}/{jenis}', [GudangController::class, 'getTerimaRakit']);
    Route::post('/seri-so', [ProduksiController::class, 'getNoseriSO']);
    Route::post('/seri-edit-so', [ProduksiController::class, 'getNoseriSOEdit']);
    Route::post('/closeRakit', [ProduksiController::class, 'closeRakit']);
    Route::post('/closeTransfer', [ProduksiController::class, 'closeTransfer']);

    // check
    Route::post('/cekStok', [ProduksiController::class, 'checkStok']);
    Route::post('/updateCheck', [ProduksiController::class, 'UncheckedNoseri']);
    Route::post('/updateChecked', [ProduksiController::class, 'checkedNoseri']);
});

Route::prefix('/hr')->group(function () {
    Route::prefix('/meet')->group(function () {
        Route::get('/getKaryawan', [MeetingController::class, 'getKaryawanForNotulen'])->middleware('jwt.verify');

        Route::prefix('/lokasi')->group(function () {
            Route::post('/store', [MeetingController::class, 'store_lokasi_meet']);
            Route::get('/show', [MeetingController::class, 'show_lokasi_meet']);
            Route::post('/update/', [MeetingController::class, 'update_lokasi_meet']);
            Route::post('/delete/', [MeetingController::class, 'delete_lokasi_meet']);
        });
        Route::prefix('/jadwal')->group(function () {
            Route::post('/', [MeetingController::class, 'store_jadwal_meet'])->middleware('jwt.verify');
            Route::get('/show_id/{id}', [MeetingController::class, 'show_jadwal_meet_id']);
            Route::get('/show/{status}', [MeetingController::class, 'show_jadwal_meet'])->middleware('jwt.verify');
            Route::get('/{id}', [MeetingController::class, 'detail_jadwal_meet']);
            Route::get('/print/{id}', [MeetingController::class, 'cetakUndangan']);
            Route::put('/{id}', [MeetingController::class, 'update_jadwal_meet'])->middleware('jwt.verify');
            Route::post('/update/{status}', [MeetingController::class, 'update_status_meet'])->middleware('jwt.verify');
            Route::get('/show_peserta/{id}', [MeetingController::class, 'show_peserta']);
            Route::get('/checkApproval/{id}', [MeetingController::class, 'checkApproval'])->middleware('jwt.verify');
        });
        Route::prefix('/show_dokumen_ftp')->group(function () {
            Route::get('/', [MeetingController::class, 'show_dokumen_ftp']);
        });
        Route::prefix('/jadwal_person')->group(function () {
            Route::get('/show/{status}', [MeetingController::class, 'show_jadwal_meet_person'])->middleware('jwt.verify');
            Route::get('/detail/{id}', [MeetingController::class, 'detail_jadwal_meet_person'])->middleware('jwt.verify');
            Route::post('/kehadiran', [MeetingController::class, 'update_hadir_jadwal_meet'])->middleware('jwt.verify');
        });
        Route::prefix('/notulen')->group(function () {
            Route::post('/verif', [MeetingController::class, 'verif_notulen_meet'])->middleware('jwt.verify');
            Route::post('/', [MeetingController::class, 'store_notulen_meet'])->middleware('jwt.verify');
            Route::get('/{id}', [MeetingController::class, 'show_notulen_meet']);
        });
        Route::prefix('/hasil')->group(function () {
            Route::post('/dokumen', [MeetingController::class, 'upload_dokumen']);
            Route::post('/dokumen_ftp', [MeetingController::class, 'upload_dokumen_ftp']);
            Route::post('/', [MeetingController::class, 'store_hasil_meet']);
            Route::get('/{id}', [MeetingController::class, 'show_hasil_meet']);
            Route::get('/print/{id}', [MeetingController::class, 'cetakHasil']);
            Route::get('/edit/{id}', [MeetingController::class, 'edit_hasil_meet']);
            Route::put('/{id}', [MeetingController::class, 'update_hasil_meet']);
            Route::delete('/{id}', [MeetingController::class, 'delete_hasil_meet']);
            Route::post('/upload', [MeetingController::class, 'upload_dok']);
        });
    });
});

Route::prefix('/prd')->group(function () {
    Route::prefix('/fg')->group(function () {
        Route::prefix('/non_jadwal')->group(function () {
            Route::post('/gen', [ProduksiController::class, 'generate_fg_non_jadwal']);
            Route::get('/show', [ProduksiController::class, 'show_non_jadwal']);
            Route::get('/detail/{id}', [ProduksiController::class, 'detail_non_jadwal']);
        });
        Route::prefix('/non_stok')->group(function () {
            Route::post('/gen', [ProduksiController::class, 'generate_fg_non_stok'])->middleware('jwt.verify');
            Route::post('/riwayat', [ProduksiController::class, 'store_noseri_fg_riwayat_nonstok'])->middleware('jwt.verify');
            Route::get('/riwayat/{id}', [ProduksiController::class, 'get_noseri_fg_riwayat_nonstok']);
            Route::post('/show', [ProduksiController::class, 'show_fg_non_stok']);
        });
        Route::post('/gen_bppb', [ProduksiController::class, 'generate_bppb']);
        Route::post('/cek_bppb', [ProduksiController::class, 'cek_bppb']);
        Route::post('/close_bppb', [ProduksiController::class, 'close_bppb']);
        Route::get('/seri_bppb/{id}', [ProduksiController::class, 'riwayat_seri_bppb']);
        Route::get('/riwayat_bppb', [ProduksiController::class, 'riwayat_selesai_bppb']);
        Route::post('/gen', [ProduksiController::class, 'generate_fg']);
        Route::post('/non_gen', [ProduksiController::class, 'non_generate_fg']);
        Route::post('/riwayat', [ProduksiController::class, 'riwayat_fg']);
        Route::post('/gen/confirm', [ProduksiController::class, 'generate_fg_confirm']);
        Route::post('/cetak/', [ProduksiController::class, 'get_noseri_fg_cetak']);
        Route::post('/riwayat_code/', [ProduksiController::class, 'store_noseri_fg_riwayat_code'])->middleware('jwt.verify');
        Route::get('/riwayat_code/{id}', [ProduksiController::class, 'get_noseri_fg_riwayat_code']);
    });
    Route::prefix('/rw')->group(function () {
        Route::post('/generate_seri_back', [ProduksiController::class, 'generate_seri_back']);
        //   Route::post('/generate_seri_peti', [ProduksiController::class, 'generate_seri_peti']);
        Route::get('/belum_kirim', [ProduksiController::class, 'belum_kirim_rw']);
        Route::get('/riwayat_permintaan', [ProduksiController::class, 'riwayat_rw_permintaan']);
        Route::get('/proses', [ProduksiController::class, 'proses_rw']);
        Route::get('/proses/produk/{id}', [ProduksiController::class, 'proses_rw_produk']);
        Route::get('/siap/produk/{id}', [ProduksiController::class, 'siap_tf_rw_produk']);
        Route::post('/permintaan', [ProduksiController::class, 'permintaan_rw'])->middleware('jwt.verify');
        Route::get('/surat_permintaan/{id}', [ProduksiController::class, 'surat_permintaan_rw']);
        Route::get('/surat_penyerahan/{divisi}/{id}', [ProduksiController::class, 'surat_penyerahan_rw']);
        Route::post('/gen', [ProduksiController::class, 'generate_rw'])->middleware('jwt.verify');
        Route::put('/gen/{id}', [ProduksiController::class, 'update_rw'])->middleware('jwt.verify');
        Route::delete('/gen/{id}', [ProduksiController::class, 'hapus_rw'])->middleware('jwt.verify');
        Route::get('/riwayat', [ProduksiController::class, 'riwayat_rw']);
        Route::get('/pack/{id}', [ProduksiController::class, 'packing_list_rw']);
        Route::post('/tf', [ProduksiController::class, 'tf_rw'])->middleware('jwt.verify');
        Route::get('/tf/riwayat', [ProduksiController::class, 'tf_riwayat_rw']);
    });
    Route::get('/kamus_prd/{year}', [ProduksiController::class, 'kamus_produk']);
    Route::get('/kamus_prd/detail/{year}/{prd}', [ProduksiController::class, 'kamus_produk_detail']);
    Route::get('/dashboard', [ProduksiController::class, 'dashboard']);
    Route::get('/allproduk', [ProduksiController::class, 'getAllProduk']);
    Route::post('/grafikproduk', [ProduksiController::class, 'getGrafikProduk']);

    Route::post('/minus10/h', [ProduksiController::class, 'h_minus10']);
    Route::post('/minus5/h', [ProduksiController::class, 'h_minus5']);
    Route::post('/exp/h', [ProduksiController::class, 'h_exp']);

    Route::post('/minus5', [ProduksiController::class, 'minus5'])->middleware('jwt.verify');
    Route::post('/minus10', [ProduksiController::class, 'minus10'])->middleware('jwt.verify');
    Route::post('/exp', [ProduksiController::class, 'expired'])->middleware('jwt.verify');

    Route::post('/exp_rakit/h', [ProduksiController::class, 'exp_rakit_h']);
    Route::post('/exp_rakit', [ProduksiController::class, 'exp_rakit'])->middleware('jwt.verify');

    Route::post('/exp_jadwal/h', [ProduksiController::class, 'exp_jadwal_h']);
    Route::post('/exp_jadwal', [ProduksiController::class, 'exp_jadwal'])->middleware('jwt.verify');

    // so
    Route::post('/so', [ProduksiController::class, 'getSOProduksi'])->middleware('jwt.verify');

    // jadwal
    // plan
    Route::post('/plan', [ProduksiController::class, 'plan_rakit']);
    Route::post('/plan-cal', [ProduksiController::class, 'calender_plan']);

    // on
    Route::get('/ongoing', [ProduksiController::class, 'on_rakit']);
    Route::get('/ongoing/{id}', [ProduksiController::class, 'on_rakit_detail']);
    Route::post('/ongoing-cal', [ProduksiController::class, 'calender_current'])->middleware('jwt.verify');
    Route::get('/ongoing/h/{id}', [ProduksiController::class, 'detailRakitHeader']);
    Route::get('/ajax_his_rakit', [ProduksiController::class, 'ajax_history_rakit']);
    Route::get('/riwayat_rakit', [ProduksiController::class, 'get_his_rakit']);
    Route::get('/ajax_perproduk', [ProduksiController::class, 'ajax_perproduk']);
    Route::get('/detail_perproduk/{id}', [ProduksiController::class, 'detail_perproduk']);
    Route::get('/product_his_rakit', [ProduksiController::class, 'product_his_rakit']);
    Route::post('/rakit-seri', [ProduksiController::class, 'storeRakitNoseri']);
    Route::post('cek-noseri', [ProduksiController::class, 'cekDuplicateNoseri']);
    Route::get('/ajax_sisa', [ProduksiController::class, 'ajax_sisa_transfer']);
    Route::post('/detail_sisa_kirim', [ProduksiController::class, 'detail_sisa_kirim']);

    Route::get('/testing', [ProduksiController::class, 'change_jadwal']);

    // kirim
    Route::get('/kirim', [ProduksiController::class, 'getSelesaiRakit']);
    Route::get('/headerSeri/{id}', [ProduksiController::class, 'getHeaderSeri']);
    Route::get('/historySeri/{id}/{value}', [ProduksiController::class, 'historySeri']);
    Route::get('/riwayat_seri_rakit/{id}/{value}', [ProduksiController::class, 'get_detail_noseri_rakit']);
    Route::get('/detailSeri1/{id}/{value}', [ProduksiController::class, 'detailSeri1']);
    Route::post('/send', [ProduksiController::class, 'kirimseri'])->middleware('jwt.verify');
    Route::post('/terimaseri', [ProduksiController::class, 'terimaseri']);
    Route::post('/delete', [ProduksiController::class, 'deleteNoseri']);
    Route::post('/deleteAll', [ProduksiController::class, 'deleteAllSeri']);
    Route::post('/updateRakitseri', [ProduksiController::class, 'updateNoseri']);
    Route::post('/cekUpdateNoseri', [ProduksiController::class, 'cekUbahNoseri']);

    // riwayat
    Route::prefix('/history')->group(function () {
        Route::post('/rakit/h', [ProduksiController::class, 'h_rakit']);
        Route::post('/unit/h', [ProduksiController::class, 'h_unit']);
        Route::get('/header/{id}', [ProduksiController::class, 'header_his_rakit']);
        Route::get('/pengiriman', [ProduksiController::class, 'h_pengiriman']);
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
    Route::get('/unit', [SparepartController::class, 'get_unit'])->middleware('jwt.verify');
    Route::get('/his-unit/{id}', [SparepartController::class, 'history_unit'])->middleware('jwt.verify');

    Route::post('/draft-tf', [SparepartController::class, 'get_draft_tf']);
    Route::post('/draft-terima', [SparepartController::class, 'get_draft_terima']);
    Route::get('/noseri/{id}', [SparepartController::class, 'headerSeri']);
    Route::get('/coba', [SparepartController::class, 'testing']);
    Route::post('/detailseri', [SparepartController::class, 'get_detail_id1']);

    // cek
    Route::post('/cekseri', [SparepartController::class, 'cekNoseriTerima']);
    Route::post('/getseri/spr', [SparepartController::class, 'getSeriDoneSpr']);
    Route::post('/getseri/unit', [SparepartController::class, 'getSeriDoneUnit']);
    Route::post('/header/spr', [SparepartController::class, 'headerNoseriSpr']);

    // select
    Route::post('/sel-m-jasa', [MasterController::class, 'select_m_jasa']);
    Route::post('/sel-m-spare', [MasterController::class, 'select_m_sparepart']);
    Route::post('/sel-spare', [MasterController::class, 'select_sparepart'])->middleware('jwt.verify');
    Route::post('/gkspr', [MasterController::class, 'select_gk_spr'])->middleware('jwt.verify');
    Route::post('/gkunit', [MasterController::class, 'select_gk_unit'])->middleware('jwt.verify');
    Route::post('/gklayout', [MasterController::class, 'select_gk_layout']);
    Route::get('/sel-tahun', [SparepartController::class, 'get_trx_tahun']);

    // store
    Route::post('/out-draft', [SparepartController::class, 'transfer_by_draft']);
    Route::post('/out-final', [SparepartController::class, 'transfer_by_final']);

    Route::post('/in-draft', [SparepartController::class, 'terima_by_draft']);
    Route::post('/in-final', [SparepartController::class, 'terima_by_final']);

    Route::post('/ubahunit', [SparepartController::class, 'updateUnit']);
    Route::post('/edit-in', [SparepartController::class, 'edit_draft_terima']);
    Route::post('/edit-out', [SparepartController::class, 'edit_draft_transfer']);

    Route::post('/updateDraft', [SparepartController::class, 'updateTerima']);
    Route::post('/updateFinal', [SparepartController::class, 'updateTerimaFinal']);
    Route::post('/deleteDraftTerima', [SparepartController::class, 'deleteDraftTerima']);
    Route::post('/deleteDraftTransfer', [SparepartController::class, 'deleteDraftTransfer']);
    Route::post('/editseri', [SparepartController::class, 'getNoseriEdit']);
    Route::post('/editseriunit', [SparepartController::class, 'getNoseriEditUnit']);
    Route::post('/editseri-out', [SparepartController::class, 'getOutSeriEdit']);
    Route::post('/editseriunit-out', [SparepartController::class, 'getOutSeriEditUnit']);

    Route::post('/uncheck', [SparepartController::class, 'uncheckNoseri']);
    Route::post('/check', [SparepartController::class, 'checkNoseri']);

    // edit transfer
    Route::post('/updateTransfer', [SparepartController::class, 'updateTransfer']);
    Route::post('/updateTransferDraft', [SparepartController::class, 'updateTransferDraft']);

    Route::prefix('/terima')->group(function () {
        Route::get('/all', [SparepartController::class, 'getProdukgudang']);
        Route::get('/detail-seri/{id}', [SparepartController::class, 'getSeriProduk']);
        Route::get('/terima-seri/{id}', [SparepartController::class, 'getSeriRakit']);
        Route::post('/submit', [SparepartController::class, 'terimaSeri']);
    });

    // history trx
    Route::prefix('/transaksi')->group(function () {
        Route::post('/by-product', [SparepartController::class, 'history_by_produk'])->middleware('jwt.verify');
        Route::post('/all', [SparepartController::class, 'historyAll'])->middleware('jwt.verify');
        Route::get('/noseri/{id}', [SparepartController::class, 'get_noseri_history'])->middleware('jwt.verify');
        Route::get('/header/{id}', [App\Http\Controllers\SparepartController::class, 'get_detail_id']);
        Route::get('/history/{id}', [App\Http\Controllers\SparepartController::class, 'get_trx'])->middleware('jwt.verify');
        Route::post('/grafik-trf', [SparepartController::class, 'grafik_trf'])->middleware('jwt.verify');
    });

    Route::prefix('/dashboard')->group(function () {
        Route::post('/tingkat', [SparepartController::class, 'byTingkat'])->middleware('jwt.verify');
        Route::post('/layout', [SparepartController::class, 'byLayout'])->middleware('jwt.verify');

        // stok
        Route::post('/stok/34', [SparepartController::class, 'stok34'])->middleware('jwt.verify');
        Route::post('/stok/510', [SparepartController::class, 'stok510'])->middleware('jwt.verify');
        Route::post('/stok/10', [SparepartController::class, 'stok10plus'])->middleware('jwt.verify');

        Route::post('/stok/34/h', [SparepartController::class, 'h_stok34']);
        Route::post('/stok/510/h', [SparepartController::class, 'h_stok510']);
        Route::post('/stok/10/h', [SparepartController::class, 'h_stok10plus']);

        // in
        Route::post('/in/36', [SparepartController::class, 'in36'])->middleware('jwt.verify');
        Route::post('/in/612', [SparepartController::class, 'in612'])->middleware('jwt.verify');
        Route::post('/in/1236', [SparepartController::class, 'in1236'])->middleware('jwt.verify');
        Route::post('/in/36plus', [SparepartController::class, 'in36plus'])->middleware('jwt.verify');

        Route::post('/in/36/h', [SparepartController::class, 'h_in36']);
        Route::post('/in/612/h', [SparepartController::class, 'h_in612']);
        Route::post('/in/1236/h', [SparepartController::class, 'h_in1236']);
        Route::post('/in/36plus/h', [SparepartController::class, 'h_in36plus']);
    });
});

Route::prefix('/noseri')->group(function () {
    Route::post('/edit/{id}', [App\Http\Controllers\NoseriController::class, 'UpdateNoSeri']);
    Route::delete('/delete/{id}', [App\Http\Controllers\NoseriController::class, 'DestroyNoSeri']);
    Route::get('/pengganti/{id}', [App\Http\Controllers\AfterSalesController::class, 'get_noseri_pengganti']);
});
Route::prefix('/produk')->group(function () {
    Route::post('/variasi_stok/{id}', [App\Http\Controllers\PenjualanController::class, 'check_variasi_jumlah']);
});

Route::prefix('/ekatalog')->group(function () {
    Route::get('data/{value}/{tahun}', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog'])->middleware('jwt.verify');
    Route::post('pengiriman/data', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog_pengiriman']);
    Route::get('all_satuan', [App\Http\Controllers\MasterController::class, 'get_ekatalog_satuan']);
    Route::get('all_deskripsi', [App\Http\Controllers\MasterController::class, 'get_ekatalog_deskripsi']);

    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_ekatalog']);
    Route::get('detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_ekatalog']);
    Route::post('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_ekatalog']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_ekatalog']);
    Route::delete('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_ekatalog']);
});

Route::prefix('/spa')->group(function () {
    Route::get('data/{value}/{tahun}', [App\Http\Controllers\PenjualanController::class, 'get_data_spa']);
    //Route::get('data/{value}/{tahun}', [App\Http\Controllers\PenjualanController::class, 'get_data_spa'])->middleware('jwt.verify');
    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spa']);
    Route::get('detail/{$id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spa']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_spa']);
    Route::delete('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_spa']);
    Route::post('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_spa']);
});

Route::prefix('/spb')->group(function () {
    Route::get('data/{value}/{tahun}', [App\Http\Controllers\PenjualanController::class, 'get_data_spb'])->middleware('jwt.verify');

    Route::get('detail/{$id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spb']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_spb']);
    Route::delete('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_spb']);
    Route::post('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_spb']);
    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spb']);
});

Route::prefix('/qc')->group(function () {
    Route::post('dashboard/data/{value}', [App\Http\Controllers\QcController::class, 'dashboard_data']);
    Route::post('dashboard/so', [App\Http\Controllers\QcController::class, 'dashboard_so']);
    Route::prefix('/kalibrasi')->group(function () {
        Route::post('store', [App\Http\Controllers\QcController::class, 'kirim_kalibrasi']);
    });

    Route::prefix('/monitoring')->group(function () {
        Route::get('/', [App\Http\Controllers\QcController::class, 'monitoring_data']);
        Route::get('/{id}', [App\Http\Controllers\QcController::class, 'monitoring_detail']);
        Route::get('/seri/{id}', [App\Http\Controllers\QcController::class, 'monitoring_seri']);
    });
    Route::get('/tf_riwayat', [App\Http\Controllers\QcController::class, 'tf_riwayat']);
    Route::prefix('/tf')->group(function () {
        Route::get('data/{status}', [App\Http\Controllers\QcController::class, 'tf_so']);
        Route::get('data/{status}/{id}', [App\Http\Controllers\QcController::class, 'tf_so_detail']);
        Route::get('data_seri/{status}/{id}', [App\Http\Controllers\QcController::class, 'tf_so_detail_seri']);
        Route::post('/{status}', [App\Http\Controllers\QcController::class, 'tf_store']);
    });
    Route::prefix('/so')->group(function () {
        Route::put('create/{jenis}/{pesanan_id}/{produk_id}', [App\Http\Controllers\QcController::class, 'create_data_qc']);
        Route::get('seri_riwayat_ganti/{gbj}/{pesanan}', [App\Http\Controllers\QcController::class, 'get_data_riwayat_seri_ganti']);
        Route::post('data/{value}', [App\Http\Controllers\QcController::class, 'get_data_so']);
        Route::post('data/selesai/{value}', [App\Http\Controllers\QcController::class, 'get_data_selesai_so']);
        Route::post('seri/{status}/{value}/{idpesanan}', [App\Http\Controllers\QcController::class, 'get_data_seri_ekatalog']);
        Route::post('part/{value}', [App\Http\Controllers\QcController::class, 'get_data_part_cek']);
        Route::post('seri/select/{seri_id?}/{produk_id}/{pesanan_id}', [App\Http\Controllers\QcController::class, 'get_data_select_seri']);
        Route::get('data_test', [App\Http\Controllers\QcController::class, 'get_data_so_qc']);
        Route::post('detail/{id}', [App\Http\Controllers\QcController::class, 'get_data_detail_so']);
        Route::get('update_modal', [App\Http\Controllers\QcController::class, 'update_modal_so']);
        Route::post('cek/part/{id}', [App\Http\Controllers\QcController::class, 'get_jumlah_cek_part']);
        Route::prefix('/riwayat')->group(function () {
            Route::get('detail_modal/{id}/{jenis}', [App\Http\Controllers\QcController::class, 'detail_modal_riwayat_so']);
            Route::get('data', [App\Http\Controllers\QcController::class, 'get_data_riwayat_pengujian']);
            Route::get('select/{id}', [App\Http\Controllers\QcController::class, 'getProdukPesananSelect']);
            Route::post('detail/{id}/{jenis}', [App\Http\Controllers\QcController::class, 'get_data_detail_riwayat_pengujian']);
        });
        Route::prefix('/laporan')->group(function () {
            Route::post('/create', [App\Http\Controllers\QcController::class, 'laporan_outgoing']);
        });
    });
});

Route::prefix('/logistik')->group(function () {
    Route::get('rw', [App\Http\Controllers\LogistikController::class, 'reworks_show']);
    Route::group(['prefix' => '/rw/pack'], function () {
        Route::get('show', [App\Http\Controllers\LogistikController::class, 'pack_reworks_show']);
        Route::post('store/{id}', [App\Http\Controllers\LogistikController::class, 'pack_reworks_store'])->middleware('jwt.verify');
        Route::get('detail/{id}', [App\Http\Controllers\LogistikController::class, 'pack_reworks_detail']);
        Route::get('details/{id}', [App\Http\Controllers\LogistikController::class, 'pack_reworks_details']);
    });
    Route::group(['prefix' => '/rw/pack_wilayah'], function () {
        Route::post('store/{urutan}', [App\Http\Controllers\LogistikController::class, 'pack_wilayah_reworks_store']);
        Route::get('show/{urutan}', [App\Http\Controllers\LogistikController::class, 'pack_wilayah_reworks_show']);
    });
    Route::group(['prefix' => '/rw/peti'], function () {
        Route::get('detail/{urut}', [App\Http\Controllers\LogistikController::class, 'peti_reworks_detail']);
        Route::post('store/{id}', [App\Http\Controllers\LogistikController::class, 'peti_reworks_store'])->middleware('jwt.verify');
        Route::put('update/{id}', [App\Http\Controllers\LogistikController::class, 'peti_reworks_update'])->middleware('jwt.verify');
        Route::get('show', [App\Http\Controllers\LogistikController::class, 'peti_reworks_show']);
    });
    Route::post('dashboard/data/{value}', [App\Http\Controllers\LogistikController::class, 'dashboard_data']);
    Route::post('dashboard/so', [App\Http\Controllers\LogistikController::class, 'dashboard_so']);
    Route::group(['prefix' => '/so'], function () {
        Route::put('create/{jenis}', [App\Http\Controllers\LogistikController::class, 'create_logistik']);
        Route::post('create_draft', [App\Http\Controllers\LogistikController::class, 'create_logistik_draft']);
        Route::post('update_draft', [App\Http\Controllers\LogistikController::class, 'update_logistik_draft']);
        // Route::get('data', [App\Http\Controllers\LogistikController::class, 'get_data_so']);
        Route::post('noseri/detail/{id}', [App\Http\Controllers\LogistikController::class, 'get_noseri_so']);
        Route::post('noseri/detail/belum_kirim/{id}/{array}', [App\Http\Controllers\LogistikController::class, 'get_noseri_so_belum_kirim']);
        Route::get('noseri/detail/selesai_kirim/{id}', [App\Http\Controllers\LogistikController::class, 'get_noseri_so_selesai_kirim']);
        Route::post('noseri/detail/selesai_kirim/data/{id}', [App\Http\Controllers\LogistikController::class, 'get_noseri_so_selesai_kirim_data']);
        // Route::get('data/detail/{id}', [App\Http\Controllers\LogistikController::class, 'get_data_detail_so']);
        Route::get('data/detail/item/{id}', [App\Http\Controllers\LogistikController::class, 'get_data_detail_item']);
        Route::post('data/detail/belum_kirim/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_data_detail_belum_kirim_so']);
        Route::post('data/detail/selesai_kirim/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_data_detail_selesai_kirim_so']);
        //   Route::get('detail/select/{produk_id}/{part_id}/{pesanan_id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_data_select_produk']);
        Route::post('detail/select/{pesanan_id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_data_select_produk']);
        Route::get('data/selesai/{years}', [App\Http\Controllers\LogistikController::class, 'get_data_selesai_so']);
        Route::get('data/{value}/{tahun}', [App\Http\Controllers\LogistikController::class, 'get_data_so']);

        Route::get('/sj_draft/{id}', [App\Http\Controllers\LogistikController::class, 'get_data_pesanan_sj_draft']);
        Route::get('/sj_draft/edit/{id}', [App\Http\Controllers\LogistikController::class, 'edit_sj']);
        Route::get('data/sj_draft/detail/{id}', [App\Http\Controllers\LogistikController::class, 'get_data_pesanan_sj_draft_detail']);
        Route::post('data/sj/{id}', [App\Http\Controllers\LogistikController::class, 'get_data_pesanan_sj']);
        Route::post('/data/sj_filter/{id}', [App\Http\Controllers\LogistikController::class, 'get_data_pesanan_filter_sj']);
        Route::get('/noseri_array/{produk_id}/{jumlah_kirim}', [App\Http\Controllers\LogistikController::class, 'get_data_noseri_array']);
    });
    Route::group(['prefix' => '/ekspedisi'], function () {
        Route::get('/all', [App\Http\Controllers\MasterController::class, 'get_data_all_ekspedisi']);
        // Route::get('/data', [App\Http\Controllers\MasterController::class, 'get_data_ekspedisi']);
        Route::get('select/{provinsi}', [App\Http\Controllers\MasterController::class, 'select_ekspedisi']);
        Route::post('detail/{id}', [App\Http\Controllers\MasterController::class, 'get_data_detail_ekspedisi']);
        Route::delete('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_ekspedisi']);
        // Route::post('create', [App\Http\Controllers\MasterController::class, 'create_ekspedisi']);
    });

    Route::group(['prefix' => '/pengiriman'], function () {
        // Route::get('/data', [App\Http\Controllers\LogistikController::class, 'get_data_pengiriman']);
        Route::post('/data/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_produk_detail_pengiriman']);
        Route::put('/update/{id}', [App\Http\Controllers\LogistikController::class, 'update_pengiriman']);
        Route::get('/status/update/{id}/{status}', [App\Http\Controllers\LogistikController::class, 'update_status_pengiriman']);
        // Route::get('/edit/{id}', [App\Http\Controllers\LogistikController::class, 'update_modal_surat_jalan'])->name('logistik.pengiriman.edit');
        // Route::get('/detail/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_pengiriman_detail_datas']);
        // Route::get('data', [App\Http\Controllers\MasterController::class, 'get_data_ekspedisi']);
        // Route::post('create', [App\Http\Controllers\MasterController::class, 'create_ekspedisi']);
        Route::group(['prefix' => '/riwayat'], function () {
            Route::post('/data/{pengiriman}/{provinsi}/{jenis_penjualan}/{years}', [App\Http\Controllers\LogistikController::class, 'get_data_riwayat_pengiriman']);
        });
    });

    Route::group(['prefix' => '/cek'], function () {
        Route::post('/no_sj/{id}/{val}', [App\Http\Controllers\LogistikController::class, 'check_no_sj']);
        Route::post('/no_resi/{val}', [App\Http\Controllers\LogistikController::class, 'check_no_resi']);
        Route::get('/no_sj_detail/{id}', [App\Http\Controllers\LogistikController::class, 'get_surat_jalan_detail']);
        Route::get('/no_sj_belum_kirim/{customer}', [App\Http\Controllers\LogistikController::class, 'get_surat_jalan_belum_kirim']);
    });
});

Route::prefix('/dc')->group(function () {
    Route::post('data/{tahun}', [App\Http\Controllers\DcController::class, 'get_data_coo']);
    Route::post('dashboard/data/{value}', [App\Http\Controllers\DcController::class, 'dashboard_data']);
    Route::post('dashboard/so', [App\Http\Controllers\DcController::class, 'dashboard_so']);
    Route::prefix('/so_in_process')->group(function () {
        Route::post('data', [App\Http\Controllers\DcController::class, 'get_data_so_in_process']);
    });
    Route::prefix('/so')->group(function () {
        Route::post('store', [App\Http\Controllers\DcController::class, 'store_coo']);
        Route::post('cancel', [App\Http\Controllers\DcController::class, 'cancel_so']);
        Route::post('update', [App\Http\Controllers\DcController::class, 'update_coo']);
        Route::put('update_tgl_kirim_coo/{value}', [App\Http\Controllers\DcController::class, 'update_tgl_kirim_coo']);
        Route::post('data/{value}', [App\Http\Controllers\DcController::class, 'get_data_so']);
        Route::post('selesai/{years}', [App\Http\Controllers\DcController::class, 'get_data_so_selesai']);
        Route::post('detail/{id}', [App\Http\Controllers\DcController::class, 'get_data_detail_so']);
        Route::post('detail/seri/{id}/{jenis}', [App\Http\Controllers\DcController::class, 'get_data_detail_seri_so']);
        Route::post('detail/seri_po/{id}/', [App\Http\Controllers\DcController::class, 'get_data_detail_seri_po']);
        Route::post('detail/seri/select/{id}/{value}', [App\Http\Controllers\DcController::class, 'get_data_detail_select_seri_so']);
    });
});

Route::prefix('/as')->group(function () {
    Route::prefix('/so')->group(function () {
        Route::post('/data', [App\Http\Controllers\AfterSalesController::class, 'get_data_so']);
        Route::post('/detail/{id}/{jenis}', [App\Http\Controllers\AfterSalesController::class, 'get_detail_pengiriman']);
    });

    Route::post('/retur_exist', [App\Http\Controllers\AfterSalesController::class, 'get_no_retur_exist']);
    Route::post('/retur_all', [App\Http\Controllers\AfterSalesController::class, 'get_all_retur']);

    Route::get('/detail/so_retur/{id}/{jenis}', [App\Http\Controllers\AfterSalesController::class, 'get_detail_so_retur']);

    Route::post('/produk_noseri_retur', [App\Http\Controllers\AfterSalesController::class, 'produk_noseri_retur']);
    Route::post('/produk_noseri_non_perbaikan', [App\Http\Controllers\AfterSalesController::class, 'produk_noseri_non_perbaikan']);
    Route::post('/retur_siap_kirim', [App\Http\Controllers\AfterSalesController::class, 'retur_siap_kirim']);
    Route::post('/barang_siap_kirim_retur', [App\Http\Controllers\AfterSalesController::class, 'barang_siap_kirim_retur']);

    Route::prefix('/penjualan')->group(function () {
        Route::post('/belum_proses', [App\Http\Controllers\AfterSalesController::class, 'get_data_so_belum_kirim']);
        Route::post('/selesai_proses', [App\Http\Controllers\AfterSalesController::class, 'get_data_so_selesai_kirim']);
    });

    Route::prefix('/retur')->group(function () {
        Route::post('/data', [App\Http\Controllers\AfterSalesController::class, 'get_data_retur']);
        Route::get('/data_detail', [App\Http\Controllers\AfterSalesController::class, 'get_data_detail_retur']);
        Route::get('/detail', [App\Http\Controllers\AfterSalesController::class, 'detail_retur']);
        Route::get('/laporan/{tgl_awal}/{tgl_akhir}', [App\Http\Controllers\AfterSalesController::class, 'laporan_retur']);
        Route::post('/data/laporan/{tgl_awal}/{tgl_akhir}', [App\Http\Controllers\AfterSalesController::class, 'data_laporan']);
    });

    Route::prefix('/list')->group(function () {
        Route::get('/so_selesai/{jenis}', [App\Http\Controllers\AfterSalesController::class, 'get_list_so_selesai']);
        Route::post('/no_seri_lama', [App\Http\Controllers\MasterController::class, 'get_all_past_no_seri']);
        Route::get('/so_selesai_paket/{id}/{jenis}', [App\Http\Controllers\AfterSalesController::class, 'get_list_so_selesai_paket']);
        Route::get('/so_selesai_paket_produk/{id}', [App\Http\Controllers\AfterSalesController::class, 'get_list_so_selesai_paket_produk']);
    });

    Route::prefix('/perbaikan')->group(function () {
        Route::post('/detail/noseri/{id}', [App\Http\Controllers\AfterSalesController::class, 'detail_noseri_perbaikan']);
        Route::post('/detail/part_pengganti/{id}', [App\Http\Controllers\AfterSalesController::class, 'detail_part_pengganti']);
        Route::get('/nomor', [App\Http\Controllers\AfterSalesController::class, 'get_no_perbaikan']);
    });

    Route::prefix('/pengiriman')->group(function () {
        Route::get('/data', [App\Http\Controllers\AfterSalesController::class, 'data_pengiriman'])->name('as.pengiriman.data');
        Route::get('/detail', [App\Http\Controllers\AfterSalesController::class, 'detail_pengiriman'])->name('as.pengiriman.detail');
        Route::post('/send', [App\Http\Controllers\AfterSalesController::class, 'send_pengiriman'])->name('as.pengiriman.send');
    });
});

Route::group(['prefix' => 'direksi', 'middleware' => 'auth'], function () {
    Route::get('dashboard', [App\Http\Controllers\DireksiController::class, 'dashboard']);
});

Route::prefix('/manager')->group(function () {
    Route::prefix('/qc')->group(function () {
        Route::post('belum_uji', [App\Http\Controllers\PpicController::class, 'qc_outgoing_belum_uji']);
        Route::post('selesai_uji', [App\Http\Controllers\PpicController::class, 'qc_outgoing_selesai_uji']);
    });

    Route::get('pesanan/{id}', [App\Http\Controllers\PpicController::class, 'pesanan']);
});
Route::get('/get_stok_pesanan', [MasterController::class, 'get_stok_pesanan']);

Route::get('karyawan_all', [App\Http\Controllers\kesehatan\KaryawanController::class, 'get_karyawan_all']);
Route::get('karyawan_lab', [App\Http\Controllers\kesehatan\KaryawanController::class, 'get_karyawan_lab']);

Route::prefix('/divisi')->group(function () {
    Route::get('karyawan/{id}', [MasterController::class, 'get_divisi_karyawan'])->middleware('jwt.verify');
});

Route::get('testingJson', [GudangController::class, 'dataTesting']);

Route::prefix('/labs')->group(function () {
    Route::prefix('/kalibrasi')->group(function () {
        Route::get('/', [App\Http\Controllers\LabController::class, 'kalibrasi_data']);
        Route::get('/riwayat', [App\Http\Controllers\LabController::class, 'kalibrasi_riwayat']);
        Route::get('/{id}', [App\Http\Controllers\LabController::class, 'kalibrasi_detail']);
        Route::get('seri/{id}', [App\Http\Controllers\LabController::class, 'kalibrasi_detail_seri']);
    });
    Route::prefix('/tf')->group(function () {
        Route::post('/', [App\Http\Controllers\LabController::class, 'transfer_store']);
        Route::get('/', [App\Http\Controllers\LabController::class, 'transfer_data']);
        Route::get('/{id}', [App\Http\Controllers\LabController::class, 'transfer_detail']);
        Route::get('seri/{id}', [App\Http\Controllers\LabController::class, 'transfer_detail_seri']);
    });
    Route::get('/tf_riwayat', [App\Http\Controllers\LabController::class, 'transfer_riwayat']);
    Route::get('/tf_riwayat_seri', [App\Http\Controllers\LabController::class, 'transfer_riwayat_seri']);
    Route::prefix('/produk')->group(function () {
        Route::get('detail_order/{id}', [App\Http\Controllers\LabController::class, 'produk_lab_detail_order']);
        Route::get('detail_produk/{id}', [App\Http\Controllers\LabController::class, 'produk_lab_detail_produk']);
        Route::get('/{id}', [App\Http\Controllers\LabController::class, 'produk_lab_edit']);
        Route::put('/{id}', [App\Http\Controllers\LabController::class, 'produk_lab_update']);
    });
    Route::prefix('/kode')->group(function () {
        Route::get('/{id}', [App\Http\Controllers\LabController::class, 'kode_lab_detail']);
        Route::get('/', [App\Http\Controllers\LabController::class, 'kode_lab_data']);
        Route::post('/', [App\Http\Controllers\LabController::class, 'kode_lab_store']);
        Route::put('/{id}', [App\Http\Controllers\LabController::class, 'kode_lab_update']);
    });

    Route::prefix('/dok')->group(function () {
        Route::get('/', [App\Http\Controllers\LabController::class, 'dok_lab_data']);
        Route::post('/', [App\Http\Controllers\LabController::class, 'dok_lab_store']);
        Route::get('/{id}', [App\Http\Controllers\LabController::class, 'dok_lab_edit']);
        Route::put('/{id}', [App\Http\Controllers\LabController::class, 'dok_lab_update']);
    });

    Route::prefix('/ruang')->group(function () {
        Route::get('/', [App\Http\Controllers\LabController::class, 'ruang_lab_data']);
        Route::post('/', [App\Http\Controllers\LabController::class, 'ruang_lab_store']);
        Route::get('/{id}', [App\Http\Controllers\LabController::class, 'ruang_lab_edit']);
        Route::put('/{id}', [App\Http\Controllers\LabController::class, 'ruang_lab_update']);
    });

    Route::prefix('/kode_milik')->group(function () {
        Route::get('/{id}', [App\Http\Controllers\LabController::class, 'kode_milik_edit']);
        Route::put('/{id}', [App\Http\Controllers\LabController::class, 'kode_milik_update']);
        Route::get('/', [App\Http\Controllers\LabController::class, 'kode_milik_data']);
        Route::post('/', [App\Http\Controllers\LabController::class, 'kode_milik_store']);
    });

    Route::prefix('/data')->group(function () {
        Route::get('{filter}', [App\Http\Controllers\LabController::class, 'lab_data']);
        Route::get('detail/{id}', [App\Http\Controllers\LabController::class, 'lab_data_detail']);
        Route::get('seri/{id}', [App\Http\Controllers\LabController::class, 'lab_data_detail_seri']);
    });

    Route::get('/laporan', [App\Http\Controllers\LabController::class, 'export_laporan']); // sertif per no kalibrasi
    Route::get('/metode_by_ruang/{ruang}', [App\Http\Controllers\LabController::class, 'metode_by_ruang']); // sertif per no kalibrasi
    Route::get('/ruang_by_metode/{metode}', [App\Http\Controllers\LabController::class, 'runag_by_metode']);
    Route::get('/ruang_and_metode', [App\Http\Controllers\LabController::class, 'ruang_and_metode']);
    Route::get('/sertif', [App\Http\Controllers\LabController::class, 'sertifikat_data']); // sertif per no kalibrasi
    Route::get('/cetak/{jenis}/{id}/{ttd}/{hal}', [App\Http\Controllers\LabController::class, 'cetak_sertifikat']);
    Route::get('/cetak_log', [App\Http\Controllers\LabController::class, 'cetak_sertifikat_log']);
    Route::get('/cetak_log_order', [App\Http\Controllers\LabController::class, 'cetak_sertifikat_log_order']);
    Route::get('/cetak_log_prd', [App\Http\Controllers\LabController::class, 'cetak_sertifikat_log_prd']);
    Route::post('/uji', [App\Http\Controllers\LabController::class, 'lab_store_uji']);
    Route::post('/ubah_jenis_pemilik', [App\Http\Controllers\LabController::class, 'ubah_jenis_pemilik']);
    Route::post('/ubah_alamat_pemilik', [App\Http\Controllers\LabController::class, 'ubah_alamat_pemilik']);
    Route::get('/riwayat_uji', [App\Http\Controllers\LabController::class, 'riwayat_uji']);
    Route::post('/riwayat_uji_laporan', [App\Http\Controllers\LabController::class, 'riwayat_uji_laporan']);
});

Route::namespace('v2')->group(__DIR__ . '/yogi/api.php');
Route::namespace('inventory')->group(__DIR__ . '/inventory/api.php');
// check connection
Route::get('/check', function () {
    try {
        DB::connection()->getPdo();
        if (DB::connection()->getDatabaseName()) {
            echo "Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName();
        } else {
            die("Could not find the database. Please check your configuration.");
        }
    } catch (\Exception $e) {
        die("Could not open connection to database server.  Please check your configuration." . $e->getMessage());
    }
});
