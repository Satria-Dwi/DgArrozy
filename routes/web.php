<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\MainAdminController;
use App\Http\Controllers\admin\SigninController;

Route::get('/', function () {
    return view('mainmenu', [
        'title' => 'RSUD Ar Rozy',
        'active' => 'home',
    ]);
});


Route::get('/signin', [SigninController::class, 'index'])->name('signin');
Route::post('/signin', [SigninController::class, 'authenticate']);
Route::post('/signout', [SigninController::class, 'signout']);

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard-data', [DashboardController::class, 'dashboardData']);


Route::middleware(['dgarrozy.auth:admin'])->group(function () {
    Route::get('/mainadmin', [MainAdminController::class, 'index']);
    Route::get('/mainadmin/pasien-summary', [MainAdminController::class, 'pasienSummary']);
    Route::get('/mainadmin/manajemendata', [MainAdminController::class, 'manajemendata']);
    Route::get('/mainadmin/tempat-tidur-bangsal', [MainAdminController::class, 'tempatTidurPerBangsal']);
    Route::get('/admin/top-penyakit-bulan-ini', [MainAdminController::class, 'topPenyakitBulanIni']);
});
