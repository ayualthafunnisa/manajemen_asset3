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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\LaporanPerbaikanController;

/*
|--------------------------------------------------------------------------
| API Routes (Wilayah/Region)
|--------------------------------------------------------------------------
*/

Route::prefix('api')->group(function () {
    Route::get('/provinces', [ProfileController::class, 'getProvinces'])->name('api.provinces');
    Route::get('/cities/{provinceCode}', [ProfileController::class, 'getCities'])->name('api.cities');
    Route::get('/districts/{cityCode}', [ProfileController::class, 'getDistricts'])->name('api.districts');
    Route::get('/villages/{districtCode}', [ProfileController::class, 'getVillages'])->name('api.villages');
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (tanpa login)
|--------------------------------------------------------------------------
*/



Route::get('/', function () {
    return view('welcome');
});

// Route registrasi
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Route untuk registrasi dengan Midtrans
Route::post('/register/payment-token', [RegisterController::class, 'getPaymentToken'])->name('register.payment.token');
Route::get('/registration-pending', [App\Http\Controllers\Auth\RegisterController::class, 'pendingApproval'])
    ->name('registration.pending');
Route::post('/register/final', [RegisterController::class, 'register'])->name('register.final');
Route::get('/activate-account/{token}', [RegisterController::class, 'activateAccount'])->name('activate.account');

// Route untuk Super Admin Approval
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->group(function () {
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('super_admin.approvals');
    Route::post('/approvals/{licenseId}/approve', [ApprovalController::class, 'approve'])->name('super_admin.approve');
    Route::post('/approvals/{licenseId}/reject', [ApprovalController::class, 'reject'])->name('super_admin.reject');
});

// Midtrans Webhook (tanpa auth)
Route::post('/midtrans/webhook', [RegisterController::class, 'midtransWebhook'])->name('midtrans.webhook');

// Route login
Route::middleware(['prevent.back'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('proseslogin');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES — semua role yang sudah login
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'prevent.back'])->group(function () {


    // Super Admin Approval Routes
    Route::prefix('admin')->middleware(['auth', 'role:super_admin'])->group(function () {
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('admin.approvals');
        Route::get('/approvals/{licenseId}', [ApprovalController::class, 'show'])->name('admin.approvals.show');
        Route::post('/approvals/{licenseId}/approve', [ApprovalController::class, 'approve'])->name('admin.approvals.approve');
        Route::post('/approvals/{licenseId}/reject', [ApprovalController::class, 'reject'])->name('admin.approvals.reject');
    });

    // Notification Routes
    Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/history', [NotificationController::class, 'history'])->name('history'); // TAMBAHKAN ROUTE INI
        Route::get('/unread', [NotificationController::class, 'getUnreadCount'])->name('unread');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clear-all');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/school', [ProfileController::class, 'updateSchool'])->name('profile.school');

    // Admin management routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::post('/store', [AdminController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroy'])->name('delete');
        Route::post('/{id}/resend-activation', [AdminController::class, 'resendActivation'])->name('resend-activation');
        Route::get('/school/{instansiId}', [AdminController::class, 'getSchoolAdmins'])->name('school-admins');
    });
    
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
    | Master Data
    |----------------------------------------------------------------------
    */
Route::middleware(['auth', 'prevent.back', 'role:super_admin,admin_sekolah,petugas', 'crud.perm'])->group(function () {
        // ── Instansi ─────────────────────────────────────────────────────────
    Route::resource('instansi', InstansiController::class);
 
    // ── Kategori ─────────────────────────────────────────────────────────
    Route::resource('kategori', KategoriController::class);
 
    // ── User ─────────────────────────────────────────────────────────────
    Route::resource('user', UserController::class);
    Route::post('user/{id}/active', [UserController::class, 'activate'])->name('user.active');
 
    // ── Lokasi ───────────────────────────────────────────────────────────
    Route::resource('lokasi', LokasiAssetController::class);
 
    // ── Asset ────────────────────────────────────────────────────────────
    Route::resource('asset', AssetController::class);
    Route::get('/asset/{id}/qrcode',    [AssetController::class, 'downloadQrCode'])->name('asset.qrcode.download');
    Route::post('asset/barcode-pdf',    [AssetController::class, 'generateBarcodePdf'])->name('asset.barcode-pdf');
 
    // ── Penyusutan ───────────────────────────────────────────────────────
    Route::resource('penyusutan', PenyusutanController::class);
    Route::get('penyusutan/{id}/pdf',   [PenyusutanController::class, 'generatePDF'])->name('penyusutan.pdf');
 
    // ── Penghapusan ──────────────────────────────────────────────────────
    Route::resource('penghapusan', PenghapusanController::class);
    // Approve/reject tetap butuh aksi write, diblokir untuk super_admin oleh middleware
    Route::post('penghapusan/{penghapusan}/approve', [PenghapusanController::class, 'approve'])->name('penghapusan.approve');
    Route::post('penghapusan/{penghapusan}/reject',  [PenghapusanController::class, 'reject'])->name('penghapusan.reject');
 
    // ── Kerusakan ────────────────────────────────────────────────────────
    Route::resource('kerusakan', KerusakanController::class);
    Route::get('/kerusakan/{id}/qrcode', [KerusakanController::class, 'downloadQrCode'])->name('kerusakan.qrcode.download');
    Route::post('kerusakan/{id}/update-status', [KerusakanController::class, 'updateStatus'])->name('kerusakan.updateStatus');
    });

    /*
    |----------------------------------------------------------------------
    | Teknisi Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:teknisi,admin_sekolah,super_admin')
         ->prefix('teknisi')
         ->group(function () {
        Route::get('/keluhan', [PerbaikanController::class, 'index'])->name('keluhan.index');
        Route::get('/keluhan/{id}', [PerbaikanController::class, 'show'])->name('keluhan.show');
        Route::get('keluhan/{kerusakanID}/create', [PerbaikanController::class, 'create'])->name('keluhan.create');
        Route::post('/keluhan/{kerusakanId}/perbaikan', [PerbaikanController::class, 'store'])->name('keluhan.perbaikan.store');
        Route::post('/perbaikan/{id}/update-status', [PerbaikanController::class, 'updateStatus'])->name('perbaikan.updateStatus');
        Route::get('/riwayat', [PerbaikanController::class, 'riwayat'])->name('riwayat.index');
        Route::get('/riwayat/{id}', [PerbaikanController::class, 'riwayatShow'])->name('riwayat.show');
        Route::get('/riwayat/{id}/pdf', [PerbaikanController::class, 'cetakPdf'])->name('riwayat.pdf');
    });

    // Laporan Perbaikan — Admin Sekolah
    Route::middleware('role:admin_sekolah,super_admin')
        ->prefix('admin/laporan-perbaikan')
        ->name('laporan_masuk.')
        ->group(function () {
        Route::get('/', [LaporanPerbaikanController::class, 'index'])->name('index');
        Route::get('/{id}/lihat', [LaporanPerbaikanController::class, 'lihat'])->name('lihat');
    });
});