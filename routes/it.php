<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

// Produk
Route::get('/produk', [App\Http\Controllers\MasterController::class, 'indexProduk']);
Route::post('/produk', [App\Http\Controllers\MasterController::class, 'postOrEditProduk']);