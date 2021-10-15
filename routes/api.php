<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::prefix('/ppic')->group(function () {
//     Route::get('part', [App\Http\Controllers\PpicController::class, 'getPart']);
// });
Route::prefix('/customer')->group(function () {
    Route::get('data', [App\Http\Controllers\MasterController::class, 'get_data_customer']);
    Route::get('create', [App\Http\Controllers\MasterController::class, 'create_customer']);
    Route::get('update', [App\Http\Controllers\MasterController::class, 'update_customer']);
    Route::get('delete', [App\Http\Controllers\MasterController::class, 'delete_customer']);
    Route::get('delete', [App\Http\Controllers\MasterController::class, 'delete_customer']);
    Route::get('check/{id}', [App\Http\Controllers\MasterController::class, 'check_customer']);
});
Route::prefix('/produk')->group(function () {
    Route::get('data', [App\Http\Controllers\MasterController::class, 'get_data_produk']);
    Route::post('create', [App\Http\Controllers\MasterController::class, 'create_produk']);
    Route::post('update', [App\Http\Controllers\MasterController::class, 'update_produk']);
    Route::delete('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_produk']);
    Route::get('check/{id}', [App\Http\Controllers\MasterController::class, 'check_produk']);
});
Route::prefix('/penjualan_produk')->group(function () {
    Route::get('data', [App\Http\Controllers\MasterController::class, 'get_data_penjualan_produk']);
    Route::post('create', [App\Http\Controllers\MasterController::class, 'create_penjualan_produk']);
    Route::post('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_penjualan_produk']);
    Route::get('detail/{id}', [App\Http\Controllers\MasterController::class, 'get_data_detail_penjualan_produk']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_detail_penjualan_produk']);
    Route::get('check/{id}', [App\Http\Controllers\MasterController::class, 'check_penjualan_produk']);
});
Route::prefix('/gbj')->group(function () {
    Route::get('data', [App\Http\Controllers\GudangController::class, 'get_data_barang_jadi']);
});
