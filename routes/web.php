<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    if (auth()->user()->divisi->id == 24) return redirect('/ppic/dashboard');
    else if (auth()->user()->divisi->id == 3) return redirect('/manager-teknik/dashboard');
    else return view('home');
})->middleware('auth');

Route::get('/home', function () {
    return redirect('/');
})->middleware('auth');

Route::middleware('auth')->prefix('/ppic')->group(function () {
    Route::view('/dashboard', 'spa.ppic.dashboard');
    Route::get('/data/{status}', function ($status) {
        return view('spa.ppic.data', ['status' => $status]);
    });
    Route::get('/jadwal/{status}', function ($status) {
        return view('spa.ppic.jadwal', ['status' => $status]);
    });

    //test
    Route::view('/bppb/{any}', 'spa.ppic.bppb');
    Route::view('/test', 'spa.ppic');
});

Route::middleware('auth')->prefix('/manager-teknik')->group(function () {
    Route::view('/dashboard', 'spa.manager_teknik.dashboard');
    Route::view('/persetujuan_jadwal', 'spa.manager_teknik.persetujuan_jadwal');
});

Route::middleware('auth')->prefix('/gbj')->group(function () {
    Route::view('/stok/{any?}', 'page.gbj.stok');
    Route::view('/penjualan/{any?}', 'page.gbj.penjualan');
    Route::view('/produk/{any?}', 'page.gbj.produk');
    Route::view('/so/{any?}', 'page.gbj.so');
    Route::view('/transferproduk', 'page.gbj.transferproduk');
    Route::view('/transferproduk', 'page.gbj.transferproduk');
    Route::view('/bso', 'page.gbj.bso');
    Route::view('/tso', 'page.gbj.tso');
    Route::view('/dp', 'page.gbj.dp');
    Route::view('/lp', 'page.gbj.lp');
    Route::view('/tp', 'page.gbj.tp');
    Route::view('/dashboard', 'page.gbj.dashboard');
});

Route::middleware('auth')->prefix('/produksi')->group(function () {
    Route::view('/dashboard', 'page.produksi.dashboard');
    Route::view('/so', 'page.produksi.so');
    Route::view('/jadwal_perakitan', 'page.produksi.jadwal_perakitan');
    Route::view('/perencanaan_perakitan', 'page.produksi.perencanaan_perakitan');
    Route::view('/riwayat_perakitan', 'page.produksi.riwayat_perakitan');
    Route::view('/pengiriman', 'page.produksi.pengiriman');
});
// Route::middleware('auth')->prefix('/penjualan')->group(function () {
//     Route::view('/produk/{any?}', 'page.penjualan.produk');
//     Route::view('/customer/{any?}', 'page.penjualan.customer');
//     Route::view('/penjualan/{any?}', 'page.penjualan.penjualan');
//     Route::view('/po/{any?}', 'page.penjualan.po');
// });

Route::group(['prefix' => 'penjualan', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.penjualan.dashboard')->name('penjualan.dashboard');

    Route::group(['prefix' => '/produk'], function () {
        Route::view('/show', 'page.penjualan.produk.show')->name('penjualan.produk.show');
        Route::view('/create', 'page.penjualan.produk.create')->name('penjualan.produk.create');
        Route::post('/store', [App\Http\Controllers\MasterController::class, 'create_penjualan_produk'])->name('penjualan.produk.store');
        Route::view('/edit', 'page.penjualan.produk.edit')->name('penjualan.produk.edit');
        Route::put('/update/{id}', [App\Http\Controllers\MasterController::class, 'update_penjualan_produk'])->name('penjualan.produk.update');
    });

    Route::group(['prefix' => '/customer'], function () {
        Route::view('/show', 'page.penjualan.customer.show')->name('penjualan.customer.show');
        Route::get('/data/{filter}', [App\Http\Controllers\MasterController::class, 'get_data_customer']);
        Route::view('/create', 'page.penjualan.customer.create')->name('penjualan.customer.create');
        Route::post('/store', [App\Http\Controllers\MasterController::class, 'create_customer'])->name('penjualan.customer.store');
        Route::put('/update/{id}', [App\Http\Controllers\MasterController::class, 'update_customer'])->name('penjualan.customer.update');
        Route::get('/detail/{id}', [App\Http\Controllers\MasterController::class, 'detail_customer'])->name('penjualan.customer.detail');
    });

    Route::group(['prefix' => '/penjualan'], function () {
        Route::view('/show', 'page.penjualan.penjualan.show')->name('penjualan.penjualan.show');
        Route::view('/create', 'page.penjualan.penjualan.create')->name('penjualan.penjualan.create');
        Route::view('/create_new', 'page.penjualan.penjualan.create_new')->name('penjualan.penjualan.create_new');

        Route::get('/ekatalog/data/{value}', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog']);
        Route::get('/spa/data', [App\Http\Controllers\PenjualanController::class, 'get_data_spa']);
        Route::get('/spb/data', [App\Http\Controllers\PenjualanController::class, 'get_data_spb']);

        Route::post('/store', [App\Http\Controllers\PenjualanController::class, 'create_penjualan'])->name('penjualan.penjualan.store');
        Route::get('/detail/ekatalog/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_ekatalog'])->name('penjualan.penjualan.detail.ekatalog');
        Route::get('/detail/spa/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spa'])->name('penjualan.penjualan.detail.spa');
        Route::get('/detail/spb/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spb'])->name('penjualan.penjualan.detail.spb');
        Route::get('/edit_ekatalog/{id}/{jenis}', [App\Http\Controllers\PenjualanController::class, 'update_penjualan'])->name('penjualan.penjualan.edit_ekatalog');
        Route::put('/update/ekatalog/{id}', [App\Http\Controllers\PenjualanController::class, 'update_ekatalog'])->name('penjualan.penjualan.update_ekatalog');
        Route::put('/update/spa/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spa'])->name('penjualan.penjualan.update_spa');
        Route::put('/update/spb/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spb'])->name('penjualan.penjualan.update_spb');
        Route::view('/edit_spa', 'page.penjualan.penjualan.edit_spa')->name('penjualan.penjualan.edit_spa');
    });

    Route::group(['prefix' => '/so'], function () {
        Route::view('/show', 'page.penjualan.so.show')->name('penjualan.so.show');
        Route::get('/create/{id}', [App\Http\Controllers\PenjualanController::class, 'view_so_ekatalog'])->name('penjualan.so.create');
        Route::put('/store/{id}', [App\Http\Controllers\PenjualanController::class, 'create_so_ekatalog'])->name('penjualan.so.ekatalog.create');
        Route::view('/edit', 'page.penjualan.so.edit')->name('penjualan.so.edit');
    });

    Route::group(['prefix' => '/lacak'], function () {
        Route::view('/show', 'page.penjualan.lacak.show')->name('penjualan.lacak.show');
    });

    Route::group(['prefix' => '/laporan'], function () {
        Route::view('/show', 'page.penjualan.laporan.show')->name('penjualan.laporan.show');
    });
    // Route::get('/dashboard', 'digidocu\DocumentsController@dashboard')->name('dc.dashboard');
    // Route::get('/dep_doc/{id?}', 'digidocu\DocumentsController@dep_doc')->name('dc.dep_doc');
});

Route::group(['prefix' => 'qc', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.qc.dashboard')->name('qc.dashboard');
    Route::group(['prefix' => '/so'], function () {
        Route::post('create/{seri_id}/{tfgbj_id}/{pesanan_id}/{produk_id}', [App\Http\Controllers\QcController::class, 'create_data_qc']);
        Route::view('/show', 'page.qc.so.show')->name('qc.so.show');
        Route::get('/detail/{id}/{value}', [App\Http\Controllers\QCController::class, 'detail_so'])->name('qc.so.detail');
        Route::view('/detail_ekatalog/{id}', 'page.qc.so.detail_ekatalog')->name('qc.so.detail_ekatalog');
        Route::view('/detail_spa/{id}', 'page.qc.so.detail_spa')->name('qc.so.detail_spa');
        Route::view('/detail_spb/{id}', 'page.qc.so.detail_spb')->name('qc.so.detail_spb');
        Route::view('/create', 'page.qc.so.create')->name('qc.so.create');
        Route::get('/edit/{seri_id}/{produk_id}/{tfgbj_id}/{pesanan_id}', [App\Http\Controllers\QcController::class, 'get_data_seri_detail_ekatalog'])->name('qc.so.edit');

        Route::group(['prefix' => '/riwayat'], function () {
            Route::view('/show', 'page.qc.so.riwayat.show')->name('qc.so.riwayat.show');
        });
        Route::group(['prefix' => '/laporan'], function () {
            Route::view('/show', 'page.qc.laporan.show')->name('qc.so.laporan.show');
        });
    });
});


Route::group(['prefix' => 'logistik', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.logistik.dashboard')->name('logistik.dashboard');

    Route::group(['prefix' => '/so'], function () {
        Route::view('/show', 'page.logistik.so.show')->name('logistik.so.show');
        Route::view('/detail/{id}', 'page.logistik.so.detail')->name('logistik.so.detail');
        Route::view('/create', 'page.logistik.so.create')->name('logistik.so.create');
        Route::view('/edit', 'page.logistik.so.edit')->name('logistik.so.edit');
        Route::group(['prefix' => '/riwayat'], function () {
            Route::view('/show', 'page.logistik.so.riwayat.show')->name('logistik.so.riwayat.show');
        });
        Route::group(['prefix' => '/laporan'], function () {
            Route::view('/show', 'page.logistik.laporan.show')->name('logistik.so.laporan.show');
        });
    });

    Route::group(['prefix' => '/ekspedisi'], function () {
        Route::view('/show', 'page.logistik.ekspedisi.show')->name('logistik.ekspedisi.show');
        Route::view('/detail/{id}', 'page.logistik.ekspedisi.detail')->name('logistik.ekspedisi.detail');
        Route::view('/create', 'page.logistik.ekspedisi.create')->name('logistik.ekspedisi.create');
        Route::view('/edit/{id}', 'page.logistik.ekspedisi.edit')->name('logistik.ekspedisi.edit');
    });

    Route::group(['prefix' => '/pengiriman'], function () {
        Route::view('/show', 'page.logistik.pengiriman.show')->name('logistik.pengiriman.show');
        Route::view('/detail/{id}', 'page.logistik.pengiriman.detail')->name('logistik.pengiriman.detail');
        Route::view('/noseri/{id}', 'page.logistik.pengiriman.noseri')->name('logistik.pengiriman.noseri');
        Route::view('/create', 'page.logistik.pengiriman.create')->name('logistik.pengiriman.create');
        Route::view('/edit/{id}', 'page.logistik.pengiriman.edit')->name('logistik.pengiriman.edit');
        Route::get('/edit/{id}/{status}', [App\Http\Controllers\LogistikController::class, 'update_modal_surat_jalan'])->name('logistik.pengiriman.edit');
        Route::get('/print', [App\Http\Controllers\LogistikController::class, 'pdf_surat_jalan'])->name('logistik.pengiriman.print');
        Route::group(['prefix' => '/riwayat'], function () {
            Route::view('/show', 'page.logistik.pengiriman.riwayat.show')->name('logistik.riwayat.show');
        });
    });
    Route::group(['prefix' => '/laporan'], function () {
        Route::view('/show', 'page.logistik.laporan.show')->name('logistik.laporan.show');
    });
});

Route::group(['prefix' => 'direksi', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.direksi.dashboard')->name('direksi.dashboard');
});

Route::group(['prefix' => 'dc', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.dc.dashboard')->name('dc.dashboard');

    Route::group(['prefix' => '/so'], function () {
        Route::view('/show', 'page.dc.so.show')->name('dc.so.show');
        Route::view('/detail/{id}', 'page.dc.so.detail')->name('dc.so.detail');
        Route::view('/create/{id}', 'page.dc.so.create')->name('dc.so.create');
        Route::group(['prefix' => '/laporan'], function () {
            Route::view('/show', 'page.dc.laporan.show')->name('dc.so.laporan.show');
        });
    });

    Route::group(['prefix' => '/coo'], function () {
        Route::view('/show', 'page.dc.coo.show')->name('dc.coo.show');
        Route::view('/detail/{id}', 'page.dc.coo.detail')->name('dc.coo.detail');
        Route::view('/create/{id}', 'page.dc.coo.create')->name('dc.coo.create');
        Route::view('/edit/{id}', 'page.dc.coo.edit')->name('dc.coo.edit');
        Route::get('/pdf', [App\Http\Controllers\DcController::class, 'pdf_coo'])->name('dc.coo.pdf');
        Route::group(['prefix' => '/laporan'], function () {
            Route::view('/show', 'page.dc.laporan.show')->name('dc.coo.laporan.show');
        });
    });
});

Route::group(['prefix' => 'as', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.as.dashboard')->name('as.dashboard');

    Route::group(['prefix' => '/so'], function () {
        Route::view('/show', 'page.as.so.show')->name('as.so.show');
        Route::view('/list/{id}', 'page.as.so.list')->name('as.so.list');
    });

    Route::group(['prefix' => '/coo'], function () {
        Route::view('/show', 'page.dc.coo.show')->name('dc.coo.show');
        Route::view('/detail/{id}', 'page.dc.coo.detail')->name('dc.coo.detail');
        Route::view('/create/{id}', 'page.dc.coo.create')->name('dc.coo.create');
        Route::view('/edit/{id}', 'page.dc.coo.edit')->name('dc.coo.edit');
        Route::get('/pdf', [App\Http\Controllers\DcController::class, 'pdf_coo'])->name('dc.coo.pdf');
        Route::group(['prefix' => '/laporan'], function () {
            Route::view('/show', 'page.dc.laporan.show')->name('dc.coo.laporan.show');
        });
    });
});
// Route::get('/provinsi', [ProvincesController::class, 'provinsi'])->name('provinsi');

Route::get('/test/{name?}', function ($name = null) {
    return $name;
});

Route::middleware('auth')->prefix('/gk')->group(function () {
    Route::view('/dashboard', 'page.gk.dashboard');
    Route::view('/gudang', 'page.gk.gudang.index');
    Route::view('/gudang/sparepart/1', 'page.gk.gudang.sparepartEdit');
    Route::view('/gudang/unit/1', 'page.gk.gudang.unitEdit');
    Route::view('/terimaProduk', 'page.gk.terima.index');
    Route::view('/terimaProduk/1', 'page.gk.terima.edit');
    Route::view('/transfer', 'page.gk.transfer.index');
    Route::view('/transfer/1', 'page.gk.transfer.edit');
    Route::view('/transaksi', 'page.gk.transaksi.index');
    Route::view('/transaksi/1', 'page.gk.transaksi.show');
});

// Route::group(['prefix' => '/gbj', 'middleware' => 'auth'], function () {
//     Route::view('/stok', 'page.gbj.stok_show');
// });
