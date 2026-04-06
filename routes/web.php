<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\LokasiAssetController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlamatController;
use App\Http\Controllers\PenyusutanController;
use App\Http\Controllers\PenghapusanController;
use App\Http\Controllers\KerusakanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Teknisi\PerbaikanController;

/*
|--------------------------------------------------------------------------
| API (alamat dinamis)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function () {
    Route::get('/indonesia/cities/{province}',    [AlamatController::class, 'getCities']);
    Route::get('/indonesia/districts/{city}',     [AlamatController::class, 'getDistricts']);
    Route::get('/indonesia/villages/{district}',  [AlamatController::class, 'getVillages']);
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/


Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login',     [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login',    [LoginController::class, 'login'])->name('proseslogin');
Route::post('/logout',   [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES — semua role yang sudah login
|--------------------------------------------------------------------------
*/

    Route::middleware('auth')->group(function () {

    // Laporan Routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::post('/filter', [LaporanController::class, 'filter'])->name('filter');
        Route::post('/preview', [LaporanController::class, 'preview'])->name('preview');
        Route::get('/export-pdf', [LaporanController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [LaporanController::class, 'exportExcel'])->name('export-excel');
    });

    /*
    |----------------------------------------------------------------------
    | Dashboard (per role)
    |----------------------------------------------------------------------
    */
    Route::get('/superadmindashboard', [DashboardController::class, 'superAdminDashboard'])->name('dashboard.superadmin');
    Route::get('/admindashboard',      [DashboardController::class, 'adminDashboard'])->name('dashboard.admin');
    Route::get('/stafdashboard',       [DashboardController::class, 'stafDashboard'])->name('dashboard.staf');
    Route::get('/teknisiDashboard',    [DashboardController::class, 'teknisiDashboard'])->name('dashboard.teknisi');

    /*
    |----------------------------------------------------------------------
    | Master Data (super_admin, admin_sekolah, petugas)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:super_admin,admin_sekolah,petugas')->group(function () {
        Route::resource('instansi',  InstansiController::class);
        Route::resource('kategori',  KategoriController::class);
        Route::resource('user',      UserController::class);
        Route::resource('lokasi',    LokasiAssetController::class);
        Route::resource('asset',     AssetController::class);
        Route::resource('penyusutan',PenyusutanController::class);

        Route::resource('penghapusan', PenghapusanController::class);
        Route::post('penghapusan/{penghapusan}/approve', [PenghapusanController::class, 'approve'])->name('penghapusan.approve');
        Route::post('penghapusan/{penghapusan}/reject',  [PenghapusanController::class, 'reject'])->name('penghapusan.reject');

        // Kerusakan — dilaporkan oleh petugas / admin
        Route::resource('kerusakan', KerusakanController::class);
        Route::post('kerusakan/{id}/update-status', [KerusakanController::class, 'updateStatus'])->name('kerusakan.updateStatus');

        // QR / Barcode
        Route::get('/asset/{id}/qrcode',      [AssetController::class, 'downloadQrCode'])->name('asset.qrcode.download');
        Route::get('/kerusakan/{id}/qrcode',  [KerusakanController::class, 'downloadQrCode'])->name('kerusakan.qrcode.download');
        Route::post('asset/barcode-pdf',      [AssetController::class, 'generateBarcodePdf'])->name('asset.barcode-pdf');
    });

    /*
    |----------------------------------------------------------------------
    | Teknisi — Keluhan & Riwayat
    |----------------------------------------------------------------------
    */
    Route::middleware('role:teknisi,admin_sekolah,super_admin')
         ->prefix('teknisi')
         ->group(function () {

        // Keluhan (kerusakan yang masuk dan perlu ditangani)
        Route::get('/keluhan',                              [PerbaikanController::class, 'index'])->name('keluhan.index');
        Route::get('/keluhan/{id}',                         [PerbaikanController::class, 'show'])->name('keluhan.show');
        Route::get('keluhan/{kerusakanID}/create', [PerbaikanController::class, 'create'])->name('keluhan.create');
        Route::post('/keluhan/{kerusakanId}/perbaikan',     [PerbaikanController::class, 'store'])->name('keluhan.perbaikan.store');

        // Update status perbaikan
        Route::post('/perbaikan/{id}/update-status',        [PerbaikanController::class, 'updateStatus'])->name('perbaikan.updateStatus');

        // Riwayat perbaikan
        Route::get('/riwayat',       [PerbaikanController::class, 'riwayat'])->name('riwayat.index');
        Route::get('/riwayat/{id}',  [PerbaikanController::class, 'riwayatShow'])->name('riwayat.show');
        Route::get('/riwayat/{id}/pdf', [PerbaikanController::class, 'cetakPdf'])->name('riwayat.pdf');
    });
});