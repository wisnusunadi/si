<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// kategori
Route::get('/kategori', [App\Http\Controllers\MasterController::class, 'indexKategori']);
Route::post('/kategori', [App\Http\Controllers\MasterController::class, 'postOrEditKategori']);
Route::delete('/kategori', [App\Http\Controllers\MasterController::class, 'deleteKategori']);

// Produk
Route::get('/produk', [App\Http\Controllers\MasterController::class, 'indexProduk']);
Route::post('/produk', [App\Http\Controllers\MasterController::class, 'postOrEditProduk']);
Route::delete('/produk', [App\Http\Controllers\MasterController::class, 'deleteProduk']);
Route::post('/changeStatusProduk', [App\Http\Controllers\MasterController::class, 'changeStatusProduk']);

// Pengajuan
Route::post('/changeGenerateProduk', [App\Http\Controllers\MasterController::class, 'changeGenerateProduk']);
Route::get('/permintaan_pengajuan_periode', [App\Http\Controllers\MasterController::class, 'get_permintaan_pengajuan']);

// Sparepart
Route::get('/sparepart', [App\Http\Controllers\MasterController::class, 'indexSparepart']);
Route::post('/sparepart', [App\Http\Controllers\MasterController::class, 'createEditSparepart']);
Route::delete('/sparepart', [App\Http\Controllers\MasterController::class, 'deleteSparepart']);
