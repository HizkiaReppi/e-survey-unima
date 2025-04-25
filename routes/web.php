<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QualityAssuranceController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SurveyController;
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
    Route::resource('/kelompok-penjaminan-mutu', QualityAssuranceController::class)->names('dashboard.quality-assurance');

    Route::prefix('/kategori')->name('dashboard.category.')
        ->controller(CategoryController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{category}', 'show')->name('show');
            Route::get('/{category}/edit', 'edit')->name('edit');
            Route::put('/{category}', 'update')->name('update');
            Route::delete('/{category}', 'destroy')->name('destroy');
            Route::get('/{category}/daftar-pertanyaan', [QuestionController::class, 'show'])->name('questions.show');
            Route::get('/{category}/kelola-pertanyaan', [QuestionController::class, 'createOrEdit'])->name('questions.create-or-edit');
            Route::post('/{category}/kelola-pertanyaan', [QuestionController::class, 'store'])->name('questions.store');
        });

    Route::prefix('/survey')->name('dashboard.survey.')
        ->controller(SurveyController::class)
        ->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/thankyou','thankyou')->name('thankyou');
            Route::get('/results/admin', 'index')->name('results.index');
            Route::get('results/details/{id}', 'show')->name('result.show');
            Route::delete('results/admin/delete/{id}', 'delete')->name('result.destroy');
        });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/admin', [ProfileController::class, 'update_admin'])->name('profile.update.admin');
    Route::patch('/profile/student', [ProfileController::class, 'update_student'])->name('profile.update.student');
    Route::patch('/profile/lecturer', [ProfileController::class, 'update_lecturer'])->name('profile.update.lecturer');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
