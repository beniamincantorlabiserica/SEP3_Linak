<?php
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeskController;

// Guest routes
Route::middleware('guest')->group(function () {
   Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
   Route::post('register', [RegisteredUserController::class, 'store']);
   
   Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
   Route::post('login', [AuthenticatedSessionController::class, 'store']);
   
   Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
   Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
   
   Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
   Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::get('/api/desk-usage-stats', [DeskController::class, 'getUsageStats'])
    ->name('desk.usage-stats')
    ->middleware(['auth', 'web']);

Route::middleware(['auth', 'web'])->group(function () {
     Route::post('/desk-notifications/respond', [DeskController::class, 'handleNotification'])->name('desk.notification.respond');
     Route::get('/desk-notifications/check', [DeskController::class, 'checkNotification'])->name('desk.notification.check');
     Route::get('/desk-positions', [DeskController::class, 'getSavedPositions'])->name('desk.positions');
 });
 

// Authenticated routes
Route::middleware('auth')->group(function () {
   Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');
   Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
   Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
   Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   // Dashboard
   Route::get('/dashboard', [DeskController::class, 'index'])
        ->name('dashboard');
        
   // Desk routes
   Route::post('/desk/position', [DeskController::class, 'setPosition'])
        ->name('desk.position');
   Route::post('/desk/position/save', [DeskController::class, 'savePosition'])
        ->name('desk.save-position');
   Route::get('/desk_api', [DeskController::class, 'index']);
   Route::get('/desk_api/{id}', [DeskController::class, 'show']);
   Route::get('/desk_api/{id}/{category}', [DeskController::class, 'show_category']);
   Route::put('/desk_api/{id}/state', [DeskController::class, 'update_category']);
   Route::get('/api/desk-positions', [DeskController::class, 'getSavedPositions']);
   Route::delete('/api/desk-positions/{position}', [DeskController::class, 'deletePosition']);
   Route::delete('/api/desk-positions/{id}', [DeskController::class, 'deletePosition'])->name('desk.delete-position');

   Route::get('/api/desk-positions', [DeskController::class, 'getSavedPositions'])->name('desk.get-positions');
});

// Public routes
Route::get('/', function () {
   return view('welcome');
});