<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');
    Route::post('/switch-user', [LoginController::class, 'switchUser'])->name('login.switch_user');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/show', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::get('/dashboard/edit', [DashboardController::class, 'edit'])->name('dashboard.edit');
    Route::put('/dashboard/update', [DashboardController::class, 'update'])->name('dashboard.update');

    Route::resource('/user', UserController::class)->middleware('role:Superadmin');
    Route::resource('/patient', PatientController::class)->middleware('role:Superadmin,Admin');
    Route::resource('/medicine', MedicineController::class)->middleware('role:Superadmin,Admin');
    Route::resource('/treatment', TreatmentController::class)->middleware('role:Superadmin,Admin');
    
    Route::get('/transaction/{transaction}/print', [TransactionController::class, 'print'])->name('transaction.print');
    Route::resource('/transaction', TransactionController::class)->middleware('role:Superadmin,Admin');

    Route::resource('/visit', VisitController::class);
    Route::post('/visit/{visit}/status', [VisitController::class, 'status'])->name('visit.status');

    Route::get('/visit/{visit}/medical-record/create', [MedicalRecordController::class, 'create'])->name('medical-record.create');
    Route::post('/visit/{visit}/medical-record', [MedicalRecordController::class, 'store'])->name('medical-record.store');

    Route::get('/report', [ReportController::class, 'index'])->middleware('role:Superadmin,Admin')->name('report.index');
    Route::get('/report/export-pdf', [ReportController::class, 'exportPdf'])->middleware('role:Superadmin,Admin')->name('report.export-pdf');

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/{setting}/update', [SettingController::class, 'update'])->name('setting.update');
});
