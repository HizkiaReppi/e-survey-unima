<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if(!auth()->check()) {
        return view('auth.login');
    } elseif (auth()->user()->role == 'admin' || auth()->user()->role == 'super-admin'){
        return redirect()->route('dashboard');
    } else {
        abort(403);
    }
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/administrator', [AdminController::class, 'index'])->middleware('password.confirm')->name('dashboard.administrator.index');
    Route::resource('/administrator', AdminController::class)->names('dashboard.administrator')->except('index');
    Route::resource('/mahasiswa', StudentController::class)->names('dashboard.student');

    Route::resource('/kategori', CategoryController::class)->names('dashboard.category');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/admin', [ProfileController::class, 'update_admin'])->name('profile.update.admin');
    Route::patch('/profile/student', [ProfileController::class, 'update_student'])->name('profile.update.student');
    Route::patch('/profile/lecturer', [ProfileController::class, 'update_lecturer'])->name('profile.update.lecturer');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
