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
    else return redirect('/home');
})->middleware('auth');

Route::get('/home', function () {
    return view('home');
})->middleware('auth');

// Route::get('/ppic/{any}', function () {
//     return view('test');
// })->where('any', '.*');

Route::middleware('auth')->prefix('/ppic')->group(function () {
    Route::view('/dashboard', 'spa.ppic.dashboard');
    Route::view('/gudang/{any}', 'spa.ppic.gudang');
    Route::view('/schedule/{any}', 'spa.ppic.jadwal');
    Route::view('/bppb/{any}', 'spa.ppic.bppb');
});

Route::middleware('auth')->prefix('/gbj')->group(function () {
    Route::view('/stok/{any?}', 'page.gbj.stok');
    Route::view('/penjualan/{any?}', 'page.gbj.penjualan');
});

Route::middleware('auth')->prefix('/penjualan')->group(function () {
    Route::view('/produk/{any?}', 'page.penjualan.produk');
    Route::view('/customer/{any?}', 'page.penjualan.customer');
    Route::view('/penjualan/{any?}', 'page.penjualan.penjualan');
});

Route::get('/test/{name?}', function ($name = null) {
    return $name;
});

// Route::group(['prefix' => '/gbj', 'middleware' => 'auth'], function () {
//     Route::view('/stok', 'page.gbj.stok_show');
// });
