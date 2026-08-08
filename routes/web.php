<?php

use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

// Halaman publik: daftar harga + kalkulator belanja
Route::get('/', [ProdukController::class, 'index'])->name('harga.index');

// Keranjang / kalkulator belanja (disimpan di session)
Route::post('/keranjang/{produk}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::patch('/keranjang/{produk}', [KeranjangController::class, 'ubah'])->name('keranjang.ubah');
Route::delete('/keranjang', [KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');

// Halaman kelola barang (tambahkan ->middleware('auth') kalau sudah pakai login)
Route::get('/kelola', [ProdukController::class, 'kelola'])->name('kelola.index');
Route::post('/kelola', [ProdukController::class, 'store'])->name('kelola.store');
Route::patch('/kelola/{produk}', [ProdukController::class, 'update'])->name('kelola.update');
Route::delete('/kelola/{produk}', [ProdukController::class, 'destroy'])->name('kelola.destroy');
Route::get('/kelola/ekspor', [ProdukController::class, 'ekspor'])->name('kelola.ekspor');