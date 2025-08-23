<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/notifications', [DashboardController::class, 'getNotifications']);
    Route::post('/dashboard/notifications/read', [DashboardController::class, 'markNotificationRead']);
    Route::post('/dashboard/notifications/read-all', [DashboardController::class, 'markAllNotificationsRead']);
    
    // Progress Routes
    Route::prefix('progress')->name('progress.')->group(function () {
        Route::get('/', [ProgressController::class, 'index'])->name('index');
        Route::get('/create', [ProgressController::class, 'create'])->name('create')
            ->middleware('role:admin,manager');
        Route::post('/', [ProgressController::class, 'store'])->name('store')
            ->middleware('role:admin,manager');
        Route::get('/{progress}', [ProgressController::class, 'show'])->name('show');
        Route::post('/{progress}/submit', [ProgressController::class, 'submitProgress'])->name('submit');
        Route::get('/{progress}/download', [ProgressController::class, 'downloadFile'])->name('download');
        Route::get('/submission/{submission}/download', [ProgressController::class, 'downloadSubmission'])->name('download-submission');
        Route::put('/{progress}/status', [ProgressController::class, 'updateStatus'])->name('update-status')
            ->middleware('role:admin,manager');
        Route::put('/submission/{submission}/status', [ProgressController::class, 'updateSubmissionStatus'])->name('update-submission-status')
            ->middleware('role:admin,manager');
    });
    
    // Document Routes
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index');
        Route::get('/create', [DocumentController::class, 'create'])->name('create');
        Route::post('/', [DocumentController::class, 'store'])->name('store');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('show');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy')
            ->middleware('role:admin');
    });
    
    // User Management Routes (Admin & Manager only)
    Route::prefix('users')->name('users.')->middleware('role:admin,manager')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')
            ->middleware('role:admin');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Auth::routes();

