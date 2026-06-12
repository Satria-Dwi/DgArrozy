<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DgarrozyAccountController;
use App\Http\Controllers\Admin\DgarrozyFinance\DgarrozyFinanceController;
use App\Http\Controllers\Admin\DgarrozyOfficer\DgarrozyOfficerController;
use App\Http\Controllers\Admin\DgarrozyRoleController;
use App\Http\Controllers\Admin\DgarrozySimrs\DashboardSimrsController;
use App\Http\Controllers\Admin\DgarrozySimrs\Dokter\DokterController;
use App\Http\Controllers\Admin\DgarrozySimrs\Dokter\KonsultasiDokterController;
use App\Http\Controllers\Admin\DgarrozySimrs\Dokter\KonsultasiPerawatController;
use App\Http\Controllers\Admin\DgarrozySimrs\ITMaster\UserController;
use App\Http\Controllers\Admin\DgarrozySimrs\LoginController;
use App\Http\Controllers\Admin\DgarrozySimrs\Manajemen\DetailTindakan\DetailTindakanController;
use App\Http\Controllers\Admin\DgarrozySimrs\Manajemen\ManajemenController;
use App\Http\Controllers\Admin\DgarrozySimrs\RekamMedis\RekamMedisController;
use App\Http\Controllers\Admin\MainAdminController;
use App\Http\Controllers\Admin\SigninController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('mainmenu', ['title' => 'RSUD Ar Rozy', 'active' => 'home',]);
});


Route::get('/signin', [SigninController::class, 'index'])->name('signin');
Route::post('/signin', [SigninController::class, 'authenticate']);
Route::post('/signout', [SigninController::class, 'signout']);

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard-data', [DashboardController::class, 'dashboardData']);

Route::middleware(['dgarrozy.auth:admin|manajemen'])->group(function () {});
Route::get('/mainadmin', [MainAdminController::class, 'index'])->name('mainadmin.index');
Route::get('/mainadmin/pasien-summary', [MainAdminController::class, 'pasienSummary']);
Route::get('/mainadmin/manajemendata', [MainAdminController::class, 'manajemendata']);
Route::get('/mainadmin/tempat-tidur-bangsal', [MainAdminController::class, 'tempatTidurPerBangsal']);
Route::get('/mainadmin/top-penyakit-bulan-ini', [MainAdminController::class, 'topPenyakitBulanIni']);
Route::get('/mainadmin/kunjungan-poli', [MainAdminController::class, 'updatepoli']);
Route::resource('/finances', DgarrozyFinanceController::class);
Route::get('/officer', [DgarrozyOfficerController::class, 'index'])->name('admin.officer.index');

Route::get('/dgarrozy-role/create', [DgarrozyRoleController::class, 'create']);
Route::post('/dgarrozy-role/store', [DgarrozyRoleController::class, 'store'])->name('admin.role.store');
Route::get('/dgarrozy-user', [DgarrozyAccountController::class, 'index'])->name('admin.account.index');
Route::get('/dgarrozy-user/create', [DgarrozyAccountController::class, 'create'])->name('admin.account.create');
Route::post('/dgarrozy-user/store', [DgarrozyAccountController::class, 'store'])->name('admin.account.store');
Route::get('/dgarrozy-user/{id}/edit', [DgarrozyAccountController::class, 'edit'])->name('admin.account.edit');
Route::put('/dgarrozy-user/{id}', [DgarrozyAccountController::class, 'update'])->name('admin.account.update');
Route::delete('/dgarrozy-user/{i    d}', [DgarrozyAccountController::class, 'destroy'])->name('admin.account.destroy');
Route::get('/dgarrozysimrs/user', [UserController::class, 'index'])->name('admin.simrs.user');

Route::get('/dgarrozysimrs/user-table', [UserController::class, 'table'])->name('admin.simrs.user-table');

Route::post('/user/add-to-userticket', [UserController::class, 'addToUserTicket'])->name('user.addToUserTicket');
Route::post('/user/remove-from-userticket', [UserController::class, 'removeFromUserTicket'])
    ->name('user.removeFromUserTicket');

Route::post('/admin/simrs/user-ticket/update-role', [UserController::class, 'updateUserTicketRole']);

Route::middleware(['dgarrozy.auth:admin'])->group(function () {});

Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/dashboard/pasien-harian', [DokterController::class, 'pasienHarian']);

Route::get('/epasien', function () {
    include base_path('epasien/index.php');
});

Route::get('/edokter', function () {
    include base_path('edokter/index.php');
});


// AREA TERPROTEKSI
Route::middleware(['simrs.login'])->group(function () {
    Route::get('/marrozy', [DashboardSimrsController::class, 'index'])->name('marrozy.dashboard');
    Route::get('/dashboard/kunjungan-poli-hari-ini', [DashboardSimrsController::class, 'chartKunjunganPoliHariIni']);
    Route::get('/mainadmin/pasien-summary', [MainAdminController::class, 'pasienSummary']);
    Route::get('/mainadmin/manajemendata', [MainAdminController::class, 'manajemendata']);
    Route::get('/mainadmin/tempat-tidur-bangsal', [MainAdminController::class, 'tempatTidurPerBangsal']);
    Route::get('/mainadmin/top-penyakit-bulan-ini', [MainAdminController::class, 'topPenyakitBulanIni']);
    Route::get('/mainadmin/kunjungan-poli', [MainAdminController::class, 'updatepoli']);
});

Route::middleware(['simrs.login:dokter'])->group(function () {
    Route::get('/dokter', [DokterController::class, 'index'])->name('marrozy.dokter');
    Route::get('/dokter/konsultasi', function () {return view('simrs.dokter.konsultasidokter.index', ['title' => 'Konsultasi Dokter']); })->name('marrozy.konsultasidokter');
    Route::get('/dokter/konsultasi/data', [KonsultasiDokterController::class, 'KonsultasiDokter'])->name('marrozy.konsultasidokter.data');
    Route::get('/dokter/konsultasi/data/{nopermintaan}', [KonsultasiDokterController::class, 'DetilKonsultasiDokter'])->name('marrozy.konsultasidokter.detail');
    Route::get('/dokter/konsultasi/history', [KonsultasiDokterController::class, 'KonsultasiDokterSelesai'])->name('marrozy.konsultasidokter.history');
    Route::get('/dokter/konsultasi/history/{nopermintaan}', [KonsultasiDokterController::class, 'DetilHistoryKonsultasiDokter'])->name('marrozy.konsultasidokter.detail.history');
    Route::get('/dokter/konsultasi/jawabanhistory/{nopermintaan}', [KonsultasiDokterController::class, 'DetilHistoryJawabanKonsultasiDokter'])->name('marrozy.jawaban.konsultasidokter.detail.history');
    Route::get('/dokter/notif-konsultasi',[KonsultasiDokterController::class, 'NotifKonsultasiBelumDijawab']);
    
    Route::get('/dokter/konsultasiperawat', function () {return view('simrs.dokter.konsultasiperawat.index', ['title' => 'Konsultasi Perawat']); })->name('marrozy.konsultasiperawat');
    Route::get('/dokter/konsultasiperawat/data', [KonsultasiPerawatController::class, 'KonsultasiPerawat'])->name('marrozy.konsultasiperawat.data');
    Route::get('/dokter/konsultasiperawat/data/{nopermintaan}', [KonsultasiPerawatController::class, 'DetilKonsultasiPerawat'])->name('marrozy.konsultasiperawat.detail');
    Route::get('/dokter/konsultasiperawat/history', [KonsultasiPerawatController::class, 'KonsultasiPerawatSelesai'])->name('marrozy.konsultasiperawat.history');
    Route::get('/dokter/konsultasiperawat/history/{nopermintaan}', [KonsultasiPerawatController::class, 'DetilHistoryKonsultasiPerawat'])->name('marrozy.konsultasiperawat.detail.history');
    Route::get('/dokter/konsultasiperawat/jawabanhistory/{nopermintaan}', [KonsultasiPerawatController::class, 'DetilHistoryJawabanKonsultasiPerawat'])->name('marrozy.jawaban.konsultasiperawat.detail.history');
    
    Route::get('/dokter/total-pasien', [DokterController::class, 'totalPasienDokterHariIni']);
    Route::get('/dokter/total-rawat-inap', [DokterController::class, 'totalPasienRawatInapDokterHariIni']);
    Route::get('/dokter/total-rawat-jalan', [DokterController::class, 'totalPasienRawatJalanDokterHariIni']);
    Route::get('/dokter/pasien-rawat-jalan-hari-ini', [DokterController::class, 'pasienRawatJalanHariIni']);
    Route::get('/dokter/pasien-rawat-inap', [DokterController::class, 'pasienRawatInap']);
    Route::get('/dokter/pasien-detail-ralan/{no_rawat}', [DokterController::class, 'pasienDetailRalan'])->where('no_rawat', '.+');
    Route::get('/dokter/pasien-detail-ranap/{no_rawat}', [DokterController::class, 'pasienDetailRanap'])->where('no_rawat', '.+');
    Route::get('/dokter/operasi', [DokterController::class, 'totalOperasiDokterHariIni']);
    Route::get('/dokter/chart-pasien', [DokterController::class, 'chartPasien']);
    Route::get('/dokter/total-operasi-hari-ini', [DokterController::class, 'operasiDokter'])->name('dashboard.dokter.total-operasi');
});

Route::middleware(['simrs.login:petugas', 'simrs.manajemen'])->group(function () {
    Route::get('/manajemen', [ManajemenController::class, 'index'])->name('manajemen.index');
    Route::get('/dashboard/laporan-dokter-realtime', [ManajemenController::class, 'laporanDokterRealtime']);
});

Route::middleware(['simrs.login:petugas', 'simrs.detailtindakan'])->group(function () {
    Route::get('/manajemen/detailtindakan', [DetailTindakanController::class, 'index'])->name('manajemen.detailtindakan.index');
    Route::get('/manajemen/detailtindakan/{jenis}', [DetailTindakanController::class, 'detailtindakan'])->name('manajemen.detailtindakan');
    Route::get('/manajemen/detailtindakan/export/{jenis}', [DetailTindakanController::class, 'exportDetailTindakan'])->name('manajemen.detailtindakan.export');
});

Route::middleware(['simrs.login:petugas', 'simrs.rm'])->group(function () {
    Route::get('/rm', [RekamMedisController::class, 'index'])->name('marrozy.rekammedis');
    Route::get('/rm/pasien/ralan', [RekamMedisController::class, 'getDataPasienRalan']);
    Route::get('/rm/pasien/ranap', [RekamMedisController::class, 'getDataPasienRanap']);
    Route::get('/rm/penyakit/list', [RekamMedisController::class, 'getPenyakitList']);
    Route::get('/rm/pasien/ralan/export', [RekamMedisController::class, 'exportRalan']);
    Route::get('/rm/pasien/ranap/export', [RekamMedisController::class, 'exportRanap']);
    Route::post('/rm/pasien/verify-ranap', [RekamMedisController::class, 'saveVerifyRanap'])->name('rekammedis.verify-ranap');
    Route::post('/rm/pasien/verify-ranap/comment', [RekamMedisController::class, 'updateComment']);
});

Route::middleware(['simrs.login:petugas', 'simrs.it'])->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('simrs.user');
    Route::get('/user/table', [UserController::class, 'table'])->name('simrs.user.table');
});
