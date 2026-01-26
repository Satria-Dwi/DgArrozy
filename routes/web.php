<?php

use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\SigninController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\MainAdminController;
use App\Http\Controllers\admin\DgarrozyFinance\DgarrozyFinanceController;
use App\Http\Controllers\admin\DgarrozyRoleController;
use App\Http\Controllers\admin\DgarrozyAccountController;
use App\Http\Controllers\Admin\DgarrozyOfficer\DgarrozyOfficerController;

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



Route::middleware(['dgarrozy.auth:admin|manajemen'])->group(function () {
    Route::get('/mainadmin', [MainAdminController::class, 'index'])->name('mainadmin.index');
    Route::get('/mainadmin/pasien-summary', [MainAdminController::class, 'pasienSummary']);
    Route::get('/mainadmin/manajemendata', [MainAdminController::class, 'manajemendata']);
    Route::get('/mainadmin/tempat-tidur-bangsal', [MainAdminController::class, 'tempatTidurPerBangsal']);
    Route::get('/mainadmin/top-penyakit-bulan-ini', [MainAdminController::class, 'topPenyakitBulanIni']);
    Route::get('/mainadmin/kunjungan-poli', [MainAdminController::class, 'updatepoli']);
    
    Route::resource('/finances', DgarrozyFinanceController::class);
    Route::get('/officer', [DgarrozyOfficerController::class, 'index'])->name('admin.officer.index');
});

Route::middleware(['dgarrozy.auth:admin'])->group(function () {
    // Role
    Route::get('/dgarrozy-role/create', [DgarrozyRoleController::class, 'create']);
    Route::post('/dgarrozy-role/store', [DgarrozyRoleController::class, 'store'])->name('admin.role.store');

    // User / Account
    Route::get('/dgarrozy-user', [DgarrozyAccountController::class, 'index'])->name('admin.account.index');
    Route::get('/dgarrozy-user/create', [DgarrozyAccountController::class, 'create'])->name('admin.account.create');
    Route::post('/dgarrozy-user/store', [DgarrozyAccountController::class, 'store'])->name('admin.account.store');

    Route::get('/dgarrozy-user/{id}/edit', [DgarrozyAccountController::class, 'edit'])->name('admin.account.edit');
    Route::put('/dgarrozy-user/{id}', [DgarrozyAccountController::class, 'update'])->name('admin.account.update');
    Route::delete('/dgarrozy-user/{id}', [DgarrozyAccountController::class, 'destroy'])->name('admin.account.destroy');
});
