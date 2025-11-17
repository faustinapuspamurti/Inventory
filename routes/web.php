<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccountController;

//ADMIN CONTROLLERS
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\data_lokawisata\LokawisataController;
use App\Http\Controllers\admin\barang_masuk\InboundController;
use App\Http\Controllers\admin\barang_keluar\OutboundController;
use App\Http\Controllers\admin\notifikasi\NotifikasiController;
use App\Http\Controllers\admin\stok_barang\StokBarangController;


//USER CONTROLLERS
use App\Http\Controllers\user\UserDashboardController;
use App\Http\Controllers\user\stok_barang\UserStokBarangController;
use App\Http\Controllers\user\notification\NotifController;
use App\Http\Controllers\user\requests\RequestController;
use App\Models\Notifikasi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout.post');


Route::prefix('admin')->middleware(['auth'])->group(function () {

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    //Akun
    Route::get('/accounts/admin', [AccountController::class, 'index_admin'])->name('accounts.admin');
    Route::post('/accounts/admin/store', [AccountController::class, 'store_admin'])->name('admin.store');
    Route::delete('/admin/{id}', [AccountController::class, 'destroy'])->name('admin.destroy');
    Route::put('/accounts/admin/{id}', [AccountController::class, 'update_admin'])->name('admin.update');
     //Akun User

    Route::get('/accounts/user', [AccountController::class, 'index_user'])->name('accounts.user');
    Route::post('/accounts/user/store', [AccountController::class, 'store_user'])->name('user.store');
    Route::delete('/user/{id}', [AccountController::class, 'destroy'])->name('user.destroy');
    Route::put('/accounts/user/{id}', [AccountController::class, 'update_user'])->name('user.update');

    Route::resource('/stok_barang', StokBarangController::class);
    Route::resource('/barang_masuk', InboundController::class);
    Route::resource('/barang_keluar', OutboundController::class); 
    Route::resource('/lokawisata', LokawisataController::class);
    Route::resource('/notifikasi', NotifikasiController::class);
    
    //Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('admin.notifikasi');
    Route::put('/notifikasi/{id}/approve', [NotifikasiController::class, 'approve'])->name('admin.notifikasi.approve');
    Route::put('/notifikasi/{id}/reject', [NotifikasiController::class, 'reject'])->name('admin.notifikasi.reject');

    //EKSPOR KE EXCEL
    Route::get('admin/barang/export', [StokBarangController::class, 'export'])->name('barang.export');
    Route::get('admin/barang_masuk/export', [InboundController::class, 'export'])->name('barang_masuk.export');
    Route::get('admin/barang_keluar/export', [OutboundController::class, 'export'])->name('barang_keluar.export');
    Route::get('admin/notifikasi/export', [OutboundController::class, 'export'])->name('notifikasi.export');
});

Route::prefix('user')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/stok-barang', [UserStokBarangController::class, 'index'])->name('stok_barang');
    Route::post('/request-barang', [UserStokBarangController::class, 'store'])->name('request.store');
    Route::get('/notifikasi', [NotifController::class, 'index'])->name('notifikasi');
    Route::get('/request', [RequestController::class, 'index'])->name('request');

});
    
