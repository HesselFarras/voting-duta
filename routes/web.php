<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\GoogleController;
use App\Models\Candidate;
use App\Http\Controllers\AdminController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/vote/{candidateId}', [VoteController::class, 'store'])->middleware('auth');
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/admin-panel', [AdminController::class, 'index']);
    Route::post('/admin/candidate', [AdminController::class, 'store']);
    Route::delete('/admin/candidate/{id}', [AdminController::class, 'destroy']);
});

Route::post('/verify-nim', [VoteController::class, 'verifyNim'])->name('verify.nim');

Route::put('/admin/candidate/{id}', [AdminController::class, 'update'])->name('admin.candidate.update');

Route::get('/', [App\Http\Controllers\VoteController::class, 'index'])->name('dashboard');

Route::post('/admin/candidate/reset/{id}', [AdminController::class, 'resetPhoto']);

require __DIR__.'/auth.php';
