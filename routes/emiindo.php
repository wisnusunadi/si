<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::group(['prefix' => '/penjualan', 'middleware' => 'auth'], function () {
    Route::group(['prefix' => '/request_emiindo'], function () {
        Route::view('/akn_po', 'page.penjualan.request_emiindo.akn_po');
        Route::view('/status_penjualan', 'page.penjualan.request_emiindo.status_penjualan');
        Route::view('/daftar_penerimaan', 'page.penjualan.request_emiindo.daftar_penerimaan');
    });
    Route::view('/{any?}', 'page.penjualan.request_emiindo.penjualan')->where('any', '.*');
});
