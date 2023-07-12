<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Produk
Route::get('/produk', [App\Http\Controllers\MasterController::class, 'indexProduk']);
Route::post('/produk', [App\Http\Controllers\MasterController::class, 'postOrEditProduk']);
Route::post('/changeStatusProduk', [App\Http\Controllers\MasterController::class, 'changeStatusProduk']);