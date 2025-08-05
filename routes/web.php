<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PriestController;
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

// Booking step 1
Route::get('/booking/step1/{service}', [App\Http\Controllers\BookingController::class, 'step1'])->middleware(['auth', 'verified'])->name('booking.step1');

// Booking step 2
Route::post('/booking/step2', [App\Http\Controllers\BookingController::class, 'step2'])->middleware(['auth', 'verified'])->name('booking.step2');

// Booking step 3
Route::post('/booking/step3', [App\Http\Controllers\BookingController::class, 'step3'])->middleware(['auth', 'verified'])->name('booking.step3');

// AJAX endpoint for getting available time slots
Route::post('/booking/available-times', [App\Http\Controllers\BookingController::class, 'getAvailableTimeSlots'])->middleware(['auth', 'verified'])->name('booking.available-times');

// Profile routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    // Services management
    Route::resource('services', App\Http\Controllers\ServiceController::class);
    
    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::get('/services/create', [AdminController::class, 'createService'])->name('services.create');
    Route::post('/services', [AdminController::class, 'storeService'])->name('services.store');
    Route::get('/services/{service}', [AdminController::class, 'showService'])->name('services.show');
    Route::get('/services/{service}/edit', [AdminController::class, 'editService'])->name('services.edit');
    Route::put('/services/{service}', [AdminController::class, 'updateService'])->name('services.update');
    Route::delete('/services/{service}', [AdminController::class, 'destroyService'])->name('services.destroy');
    
    // Service form fields management
    Route::get('/services/{service}/form-fields', [App\Http\Controllers\ServiceFormFieldController::class, 'index'])->name('services.form-fields');
    Route::post('/services/{service}/form-fields', [App\Http\Controllers\ServiceFormFieldController::class, 'store'])->name('services.form-fields.store');
    Route::put('/services/{service}/form-fields/{formField}', [App\Http\Controllers\ServiceFormFieldController::class, 'update'])->name('services.form-fields.update');
    Route::delete('/services/{service}/form-fields/{formField}', [App\Http\Controllers\ServiceFormFieldController::class, 'destroy'])->name('services.form-fields.destroy');
    
    // Users management
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
    
    // Priests management
    Route::resource('priests', App\Http\Controllers\PriestController::class);
    
    // Settings
    Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
});
