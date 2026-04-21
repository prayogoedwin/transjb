<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\SimpanPinjamController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenyesuaianStokController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\Settings\BackupController;
// Maintenance routes (admin only)
// Route::get('/clear-cache', [MaintenanceController::class, 'clearCache'])->name('maintenance.clear-cache');
// Route::get('/clear-log', [MaintenanceController::class, 'clearLog'])->name('maintenance.clear-log');
// Route::get('/clear-all', [MaintenanceController::class, 'clearAll'])->name('maintenance.clear-all');

// Route::get('/', function () {
//     return view('welcome');
// })->name('dashboard');

Route::get('/', function () {
    // Redirect langsung ke URL
    return redirect('/dashboard');
});



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
    Route::put('settings/appearance', [Settings\AppearanceController::class, 'update'])->name('settings.appearance.update');
    Route::get('backup', function () {
        return view('settings.backup');
    })->name('backup.index')->middleware('role:admin');

    // Roles Management - dengan permission check
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:view-roles');
    Route::get('roles/export', [RoleController::class, 'export'])->name('roles.export')->middleware('permission:download-roles');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:create-roles');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:create-roles');
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('permission:show-roles');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:edit-roles');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:edit-roles');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:delete-roles');
    
    // Permissions Management - dengan permission check
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:view-permissions');
    Route::get('permissions/export', [PermissionController::class, 'export'])->name('permissions.export')->middleware('permission:download-permissions');
    Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:create-permissions');
    Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:create-permissions');
    Route::get('permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show')->middleware('permission:show-permissions');
    Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:edit-permissions');
    Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:edit-permissions');
    Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:delete-permissions');
    
    // Users Management - dengan permission check
    Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:view-users');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export')->middleware('permission:download-users');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create-users');
    Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create-users');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:show-users');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit-users');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit-users');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete-users');
    
    // Products Management - dengan permission check
    Route::get('products', [ProductController::class, 'index'])->name('products.index')->middleware('permission:view-products');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create')->middleware('permission:create-products');
    Route::post('products', [ProductController::class, 'store'])->name('products.store')->middleware('permission:create-products');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('permission:show-products');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('permission:edit-products');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('permission:edit-products');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:delete-products');

    // Nasabah Management - dengan permission check
    Route::get('nasabah', [NasabahController::class, 'index'])->name('nasabah.index')->middleware('permission:view-nasabah');
    Route::get('nasabah/export', [NasabahController::class, 'export'])->name('nasabah.export')->middleware('permission:download-nasabah');
    Route::get('nasabah/{nasabah}/export-pdf', [NasabahController::class, 'exportPdf'])->name('nasabah.export-pdf')->middleware('permission:show-nasabah');
    Route::get('nasabah/create', [NasabahController::class, 'create'])->name('nasabah.create')->middleware('permission:create-nasabah');
    Route::post('nasabah', [NasabahController::class, 'store'])->name('nasabah.store')->middleware('permission:create-nasabah');
    Route::get('nasabah/{nasabah}', [NasabahController::class, 'show'])->name('nasabah.show')->middleware('permission:show-nasabah');
    Route::get('nasabah/{nasabah}/edit', [NasabahController::class, 'edit'])->name('nasabah.edit')->middleware('permission:edit-nasabah');
    Route::put('nasabah/{nasabah}', [NasabahController::class, 'update'])->name('nasabah.update')->middleware('permission:edit-nasabah');
    Route::delete('nasabah/{nasabah}', [NasabahController::class, 'destroy'])->name('nasabah.destroy')->middleware('permission:delete-nasabah');
    
    // Bayar & Hutang Management - dengan permission check
    Route::get('simpan_pinjam', [SimpanPinjamController::class, 'index'])->name('simpan_pinjam.index')->middleware('permission:view-simpan-pinjam');
    Route::get('simpan_pinjam/create', [SimpanPinjamController::class, 'create'])->name('simpan_pinjam.create')->middleware('permission:create-simpan-pinjam');
    Route::post('simpan_pinjam', [SimpanPinjamController::class, 'store'])->name('simpan_pinjam.store')->middleware('permission:create-simpan-pinjam');
    Route::get('simpan_pinjam/export', [SimpanPinjamController::class, 'exportExcel'])->name('simpan_pinjam.export')->middleware('permission:download-simpan-pinjam');
    Route::get('simpan_pinjam/{simpanPinjam}', [SimpanPinjamController::class, 'show'])->name('simpan_pinjam.show')->middleware('permission:show-simpan-pinjam');
    Route::get('simpan_pinjam/{simpanPinjam}/print', [SimpanPinjamController::class, 'printReceipt'])->name('simpan_pinjam.printReceipt')->middleware('permission:show-simpan-pinjam');
    Route::get('simpan_pinjam/{simpanPinjam}/edit', [SimpanPinjamController::class, 'edit'])->name('simpan_pinjam.edit')->middleware('permission:edit-simpan-pinjam');
    Route::put('simpan_pinjam/{simpanPinjam}', [SimpanPinjamController::class, 'update'])->name('simpan_pinjam.update')->middleware('permission:edit-simpan-pinjam');
    Route::delete('simpan_pinjam/{simpanPinjam}', [SimpanPinjamController::class, 'destroy'])->name('simpan_pinjam.destroy')->middleware('permission:delete-simpan-pinjam');
    
    // Pembelian Management - dengan permission check
    Route::get('pembelian', [PembelianController::class, 'index'])->name('pembelian.index')->middleware('permission:view-pembelian');
    Route::get('pembelian/create', [PembelianController::class, 'create'])->name('pembelian.create')->middleware('permission:create-pembelian');
    Route::post('pembelian', [PembelianController::class, 'store'])->name('pembelian.store')->middleware('permission:create-pembelian');
    Route::get('pembelian/export', [PembelianController::class, 'exportExcel'])->name('pembelian.export')->middleware('permission:download-pembelian');
    Route::get('pembelian/{pembelian}', [PembelianController::class, 'show'])->name('pembelian.show')->middleware('permission:show-pembelian');
    Route::get('pembelian/{pembelian}/print', [PembelianController::class, 'printInvoice'])->name('pembelian.printInvoice')->middleware('permission:show-pembelian');
    Route::get('pembelian/{pembelian}/edit', [PembelianController::class, 'show'])->name('pembelian.edit')->middleware('permission:edit-pembelian');
    // Route::put('pembelian/{pembelian}', [PembelianController::class, 'update'])->name('pembelian.update')->middleware('permission:edit-pembelian');
    // Route::delete('pembelian/{pembelian}', [PembelianController::class, 'destroy'])->name('pembelian.destroy')->middleware('permission:delete-pembelian');
    
    // Penjualan Management - dengan permission check
    Route::get('penjualan', [PenjualanController::class, 'index'])->name('penjualan.index')->middleware('permission:view-penjualan');
    Route::get('penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create')->middleware('permission:create-penjualan');
    Route::post('penjualan', [PenjualanController::class, 'store'])->name('penjualan.store')->middleware('permission:create-penjualan');
    Route::get('penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.show')->middleware('permission:show-penjualan');
    Route::get('penjualan/{penjualan}/print', [PenjualanController::class, 'printInvoice'])->name('penjualan.printInvoice')->middleware('permission:show-penjualan');
    Route::get('penjualan/{penjualan}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit')->middleware('permission:edit-penjualan');
    Route::put('penjualan/{penjualan}', [PenjualanController::class, 'update'])->name('penjualan.update')->middleware('permission:edit-penjualan');
    Route::delete('penjualan/{penjualan}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy')->middleware('permission:delete-penjualan');
    
    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
    Route::get('laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
<<<<<<< HEAD
    
    // Route::prefix('api/backups')->middleware('role:admin')->group(function () {
    //     Route::post('/', [BackupController::class, 'backup'])->name('backups.create');
    //     Route::get('/', [BackupController::class, 'list'])->name('backups.list');
    //     Route::get('/history', [BackupController::class, 'history'])->name('backups.history');
    //     Route::get('/restore-history', [BackupController::class, 'restoreHistory'])->name('backups.restore-history');
    //     Route::get('/stats', [BackupController::class, 'stats'])->name('backups.stats');
    //     Route::get('/{backupName}/metadata', [BackupController::class, 'metadata'])->name('backups.metadata');
    //     Route::get('/{backupName}/download', [BackupController::class, 'download'])->name('backups.download');
    //     Route::post('/restore', [BackupController::class, 'restore'])->name('backups.restore');
    //     Route::delete('/', [BackupController::class, 'delete'])->name('backups.delete');
    // });
=======

    // Penyesuaian Stok
    Route::get('penyesuaian_stok', [PenyesuaianStokController::class, 'index'])->name('penyesuaian_stok.index')->middleware('permission:view-penyesuaian-stok');
    Route::get('penyesuaian_stok/create', [PenyesuaianStokController::class, 'create'])->name('penyesuaian_stok.create')->middleware('permission:create-penyesuaian-stok');
    Route::post('penyesuaian_stok', [PenyesuaianStokController::class, 'store'])->name('penyesuaian_stok.store')->middleware('permission:create-penyesuaian-stok');
    Route::get('penyesuaian_stok/{penyesuaianStok}', [PenyesuaianStokController::class, 'show'])->name('penyesuaian_stok.show')->middleware('permission:show-penyesuaian-stok');
>>>>>>> master
});


require __DIR__.'/auth.php';
