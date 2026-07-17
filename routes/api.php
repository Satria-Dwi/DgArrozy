<?php

use App\Http\Controllers\Admin\DgarrozySimrs\DashboardSimrsController;
use App\Http\Controllers\Admin\MainAdminController;
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

// Route::middleware(['auth:sanctum','throttle:30,1'])->get('/v1/bed-info',[MainAdminController::class, 'APItempatTidurPerBangsal']);
// Route::get('/v1/bed-info',[MainAdminController::class,'APItempatTidurPerBangsal'])->middleware('throttle:10,1');
Route::get('/v1/bed-info', [MainAdminController::class, 'APItempatTidurPerBangsal'])->middleware('throttle:bed-info');
Route::prefix('dashboard')->group(function () {

    Route::get('/', [DashboardSimrsController::class, 'dashboardData']);

    Route::get('/kunjungan-poli-hari-ini', [DashboardSimrsController::class, 'chartKunjunganPoliHariIni']);

    Route::get('/pasien-summary', [MainAdminController::class, 'pasienSummary']);

    Route::get('/manajemendata', [MainAdminController::class, 'manajemendata']);

    Route::get('/tempat-tidur-bangsal', [MainAdminController::class, 'tempatTidurPerBangsal']);

    Route::get('/top-penyakit-bulan-ini', [MainAdminController::class, 'topPenyakitBulanIni']);

    Route::get('/kunjungan-poli', [MainAdminController::class, 'updatepoli']);
});

// Endpoint utama dashboard
Route::get('/dashboard-data', [DashboardSimrsController::class, 'dashboardData']);
