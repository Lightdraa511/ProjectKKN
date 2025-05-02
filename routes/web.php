<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\LocationManagementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
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

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Auth routes
Route::middleware('guest')->group(function () {
    // User auth
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Admin auth
    Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
});

// Logout routes (available for authenticated users)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout')->middleware('auth:admin');

// Midtrans callback (no middleware)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');

// User routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Payment process
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/waiting', [PaymentController::class, 'waiting'])->name('payment.waiting');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/payment/check-status', [PaymentController::class, 'checkStatus'])->name('payment.checkStatus');

    // Location selection
    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/{location}/select', [LocationController::class, 'select'])->name('locations.select');
    Route::post('/locations/{location}/confirm', [LocationController::class, 'confirm'])->name('locations.confirm');
    Route::delete('/locations/cancel', [LocationController::class, 'cancel'])->name('locations.cancel');

    // Profile completion
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/completion', [ProfileController::class, 'completion'])->name('profile.completion');
});

// Admin routes
Route::prefix('admin')->middleware(['auth:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Location management
    Route::resource('locations', LocationManagementController::class);
    Route::get('/locations/{location}/students', [LocationManagementController::class, 'students'])->name('locations.students');

    // Faculty management
    Route::resource('faculties', FacultyController::class);

    // User management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/reset-progress', [UserController::class, 'resetProgress'])->name('users.reset-progress');
    Route::get('/users-export', [UserController::class, 'export'])->name('users.export');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

    // Account settings
    Route::get('/account', [AdminController::class, 'account'])->name('account');
    Route::post('/account', [AdminController::class, 'updateAccount'])->name('account.update');
});
