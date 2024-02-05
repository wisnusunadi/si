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