<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\SparepartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Auth::routes();

// Manager Gudang Barang Jadi

Route::group(['prefix' => '/gbjmanager', 'middleware' => 'auth'], function ()
{
    Route::view('/produksi', 'manager.gbj.produksi');
});
