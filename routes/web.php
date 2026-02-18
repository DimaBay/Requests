<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/requests/create');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/requests/create', [RequestController::class, 'create'])->name('requests.create');
Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');

Route::middleware(['auth', 'role:dispatcher'])->prefix('dispatcher')->name('dispatcher.')->group(function () {
    Route::get('/', [\App\Http\Controllers\DispatcherController::class, 'index'])->name('index');
    Route::post('/{repairRequest}/assign', [\App\Http\Controllers\DispatcherController::class, 'assign'])->name('assign');
    Route::post('/{repairRequest}/cancel', [\App\Http\Controllers\DispatcherController::class, 'cancel'])->name('cancel');
});

Route::middleware(['auth', 'role:master'])->prefix('master')->name('master.')->group(function () {
    Route::get('/', [\App\Http\Controllers\MasterController::class, 'index'])->name('index');
    Route::post('/{repairRequest}/start', [\App\Http\Controllers\MasterController::class, 'start'])->name('start');
    Route::post('/{repairRequest}/done', [\App\Http\Controllers\MasterController::class, 'done'])->name('done');
});
