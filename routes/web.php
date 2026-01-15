<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\SigninController;

Route::get('/', function () {
    return view('mainmenu',[
        'title' => 'mainmenu',
        'active' => 'home',
    ]);
});

Route::get('/signin', [SigninController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard-data', [DashboardController::class, 'dashboardData']);