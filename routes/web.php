<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMaster\VendorController;
use App\Http\Controllers\DataMaster\BarangController;
use App\Http\Controllers\DataMaster\SatuanController;
use App\Http\Controllers\DataMaster\MarginPenjualanController;
use App\Http\Controllers\DataMaster\RoleController;
use App\Http\Controllers\DataMaster\UserController;
use App\Http\Controllers\Transaksi\PengadaanController;
use App\Http\Controllers\Transaksi\PenerimaanController;
use App\Http\Controllers\Transaksi\PenjualanController;
use App\Http\Controllers\Transaksi\ReturController;
use App\Http\Controllers\KartuStokController;
use App\Http\Controllers\StokBarangController;
use App\Http\Controllers\Detail\DetailPengadaanController; 
use App\Http\Controllers\Detail\DetailPenerimaanController;
use App\Http\Controllers\Detail\DetailPenjualanController;
use App\Http\Controllers\Detail\DetailReturController;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\IsSuperAdmin;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(isAdmin::class)->group(function () { 

        Route::prefix('data-stok')->group(function () {
            Route::get('/kartu-stok', [KartuStokController::class, 'index'])->name('kartu-stok.index');
            Route::get('/stok-barang', [StokBarangController::class, 'index'])->name('stok-barang.index');
        });
        
        Route::prefix('transaksi')->group(function () {
            Route::get('/penjualan', [PenjualanController::class, 'index'])->name('transaksi.penjualan.index');
            Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('transaksi.penjualan.create');
            Route::post('/penjualan', [PenjualanController::class, 'store'])->name('transaksi.penjualan.store');
            Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('transaksi.penjualan.show');
        });

        Route::prefix('detail')->group(function () {
            Route::get('/penjualan', [DetailPenjualanController::class, 'index'])->name('detail.penjualan.index');
        });

    });

    Route::middleware(IsSuperAdmin::class)->group(function () { 

        Route::prefix('datamaster')->group(function () {
            
            Route::get('/vendor/index-default', [VendorController::class, 'index'])->name('vendor.index');
            Route::get('/vendor/index-all', [VendorController::class, 'showAll'])->name('vendor.all');
            Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
            Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');
            Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
            Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');
            Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');

            Route::get('/barang/index-default', [BarangController::class, 'index'])->name('barang.index');
            Route::get('/barang/index-all', [BarangController::class, 'showAll'])->name('barang.all');
            Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
            Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
            Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
            Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
            Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

            Route::get('/satuan/index-default', [SatuanController::class, 'index'])->name('satuan.index');
            Route::get('/satuan/index-all', [SatuanController::class, 'showAll'])->name('satuan.all');
            Route::get('/satuan/create', [SatuanController::class, 'create'])->name('satuan.create');
            Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
            Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
            Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
            Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');

            Route::get('/margin-penjualan/index-default', [MarginPenjualanController::class, 'index'])->name('margin.index');
            Route::get('/margin-penjualan/index-all', [MarginPenjualanController::class, 'showAll'])->name('margin.all');
            Route::get('/margin-penjualan/create', [MarginPenjualanController::class, 'create'])->name('margin.create');
            Route::post('/margin-penjualan', [MarginPenjualanController::class, 'store'])->name('margin.store');
            Route::get('/margin-penjualan/{id}/edit', [MarginPenjualanController::class, 'edit'])->name('margin.edit');
            Route::put('/margin-penjualan/{id}', [MarginPenjualanController::class, 'update'])->name('margin.update');
            Route::delete('/margin-penjualan/{id}', [MarginPenjualanController::class, 'destroy'])->name('margin.destroy');

            Route::get('/role/index', [RoleController::class, 'index'])->name('admin.role.index');
            Route::get('/role/create', [RoleController::class, 'create'])->name('admin.role.create');
            Route::post('/role', [RoleController::class, 'store'])->name('admin.role.store');
            Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('admin.role.edit');
            Route::put('/role/{id}', [RoleController::class, 'update'])->name('admin.role.update');
            Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('admin.role.destroy');
            
            Route::get('/user/index', [UserController::class, 'index'])->name('admin.user.index');
            Route::get('/user/create', [UserController::class, 'create'])->name('admin.user.create');
            Route::post('/user', [UserController::class, 'store'])->name('admin.user.store');
            Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
            Route::put('/user/{id}', [UserController::class, 'update'])->name('admin.user.update');
            Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
        });

        Route::prefix('transaksi')->group(function () {
            Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('transaksi.pengadaan.index');
            Route::get('/pengadaan/create', [PengadaanController::class, 'create'])->name('transaksi.pengadaan.create');
            Route::post('/pengadaan', [PengadaanController::class, 'store'])->name('transaksi.pengadaan.store');
            Route::get('/pengadaan/{id}', [PengadaanController::class, 'show'])->name('transaksi.pengadaan.show');
            Route::put('/pengadaan/{id}/cancel', [PengadaanController::class, 'cancel'])->name('transaksi.pengadaan.cancel');

            Route::get('/penerimaan', [PenerimaanController::class, 'index'])->name('transaksi.penerimaan.index');
            Route::get('/penerimaan/create', [PenerimaanController::class, 'create'])->name('transaksi.penerimaan.create');
            Route::post('/penerimaan', [PenerimaanController::class, 'store'])->name('transaksi.penerimaan.store');
            Route::get('/penerimaan/{id}', [PenerimaanController::class, 'show'])->name('transaksi.penerimaan.show');

            Route::get('/retur', [ReturController::class, 'index'])->name('transaksi.retur.index');
            Route::get('/retur/create', [ReturController::class, 'create'])->name('transaksi.retur.create');
            Route::post('/retur', [ReturController::class, 'store'])->name('transaksi.retur.store');
            Route::get('/retur/{id}', [ReturController::class, 'show'])->name('transaksi.retur.show');
        });

        Route::prefix('detail')->group(function () {
            Route::get('/pengadaan', [DetailPengadaanController::class, 'index'])->name('detail.pengadaan.index');
            Route::get('/penerimaan', [DetailPenerimaanController::class, 'index'])->name('detail.penerimaan.index');
            Route::get('/retur', [DetailReturController::class, 'index'])->name('detail.retur.index');
        });
    });
});