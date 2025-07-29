<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    
    $user = Auth::user();
    if ($user->first_login) {
        $user->update(['first_login' => false]);
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('landing');
    }
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('resent', true);
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// Dashboard for first-time users
Route::get('/dashboard', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// Landing page for returning users
Route::get('/landing', function () {
    return view('landing-page');
})->middleware(['auth', 'verified'])->name('landing');

// Services routes
Route::get('/services', [ServiceController::class, 'index'])->middleware(['auth', 'verified'])->name('services');
Route::get('/services/book', [ServiceController::class, 'book'])->middleware(['auth', 'verified'])->name('services.book');

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'verified'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::get('/services/create', [AdminController::class, 'createService'])->name('services.create');
    Route::post('/services', [AdminController::class, 'storeService'])->name('services.store');
    Route::get('/services/{service}', [AdminController::class, 'showService'])->name('services.show');
    Route::get('/services/{service}/edit', [AdminController::class, 'editService'])->name('services.edit');
    Route::put('/services/{service}', [AdminController::class, 'updateService'])->name('services.update');
    Route::delete('/services/{service}', [AdminController::class, 'destroyService'])->name('services.destroy');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});
