<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\SaranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('', function ()  {
    return response()->json([
        'status' => false,
        'message' => 'tidak ada izin akses',
    ]);
})->name('login');

Route::post('registerUser',[AuthController::class,'registerUser']);
Route::post('loginUser',[AuthController::class,'loginUser']);
Route::post('laporan',[PeminjamanController::class,'laporanHTML']);

Route::apiResource('barang', BarangController::class)->middleware('auth:sanctum');
Route::apiResource('kontak', KontakController::class)->middleware('auth:sanctum');
Route::apiResource('saran', SaranController::class)->middleware('auth:sanctum');
Route::apiResource('peminjaman', PeminjamanController::class)->middleware('auth:sanctum');


