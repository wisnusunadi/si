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
