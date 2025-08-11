<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test-page');
});

Route::get('/setup', function () {
    return view('setup-database');
});

// Email and Registration Testing Routes
Route::get('/test-email', [App\Http\Controllers\TestController::class, 'testEmail'])->name('test.email');
Route::post('/test-email', [App\Http\Controllers\TestController::class, 'sendTestEmail'])->name('test.email.send');
Route::get('/test-registration', [App\Http\Controllers\TestController::class, 'testRegistration'])->name('test.registration');
Route::post('/test-user', [App\Http\Controllers\TestController::class, 'createTestUser'])->name('test.user.create');

// Legal Pages
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

// Email Verification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'show'])
        ->name('verification.notice');
    
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::get('/email/verify/{id}/{token}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isNutritionist()) {
        return redirect()->route('nutritionist.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-children', [ProfileController::class, 'myChildren'])->name('profile.my-children');
});

// Admin Routes
Route::middleware(['auth', 'admin', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/patients', [App\Http\Controllers\AdminController::class, 'patients'])->name('patients');
    Route::post('/patients', [App\Http\Controllers\AdminController::class, 'storePatient'])->name('patients.store');
    Route::get('/patients/{patient}', [App\Http\Controllers\AdminController::class, 'showPatient'])->name('patients.show');
    Route::get('/nutrition', [App\Http\Controllers\AdminController::class, 'nutrition'])->name('nutrition');
    Route::post('/nutrition', [App\Http\Controllers\AdminController::class, 'storeNutrition'])->name('nutrition.store');
    Route::get('/nutrition/{nutrition}', [App\Http\Controllers\AdminController::class, 'showNutrition'])->name('nutrition.show');
    Route::get('/reports', [App\Http\Controllers\AdminController::class, 'reports'])->name('reports');
    // Inventory Management
    Route::get('/inventory', [App\Http\Controllers\AdminController::class, 'inventory'])->name('inventory');
    Route::post('/inventory', [App\Http\Controllers\AdminController::class, 'storeInventory'])->name('inventory.store');
    Route::get('/inventory/{inventory}', [App\Http\Controllers\AdminController::class, 'showInventory'])->name('inventory.show');
    Route::get('/inventory/{inventory}/edit', [App\Http\Controllers\AdminController::class, 'editInventory'])->name('inventory.edit');
    Route::put('/inventory/{inventory}', [App\Http\Controllers\AdminController::class, 'updateInventory'])->name('inventory.update');
    Route::delete('/inventory/{inventory}', [App\Http\Controllers\AdminController::class, 'destroyInventory'])->name('inventory.destroy');
    Route::post('/inventory/transaction', [App\Http\Controllers\AdminController::class, 'storeTransaction'])->name('inventory.transaction');
    Route::get('/transactions', [App\Http\Controllers\AdminController::class, 'transactions'])->name('transactions');
    Route::post('/transactions', [App\Http\Controllers\AdminController::class, 'storeTransaction'])->name('transactions.store');

    // Email Templates Management
    Route::get('/email-templates', [App\Http\Controllers\Admin\EmailTemplateController::class, 'index'])->name('email-templates.index');
    Route::get('/email-templates/{emailTemplate}/edit', [App\Http\Controllers\Admin\EmailTemplateController::class, 'edit'])->name('email-templates.edit');
    Route::put('/email-templates/{emailTemplate}', [App\Http\Controllers\Admin\EmailTemplateController::class, 'update'])->name('email-templates.update');
    Route::get('/email-templates/{emailTemplate}/preview', [App\Http\Controllers\Admin\EmailTemplateController::class, 'preview'])->name('email-templates.preview');
    Route::get('/email-templates/create-default', [App\Http\Controllers\Admin\EmailTemplateController::class, 'createDefault'])->name('email-templates.create-default');

    // API Test Interface
    Route::get('/api-test', function () {
        return view('admin.api-test');
    })->name('api-test');
});

// Nutritionist Routes
Route::middleware(['auth', 'nutritionist', 'verified'])->prefix('nutritionist')->name('nutritionist.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\NutritionistController::class, 'dashboard'])->name('dashboard');
    Route::get('/patients', [App\Http\Controllers\NutritionistController::class, 'patients'])->name('patients');
    Route::post('/patients', [App\Http\Controllers\NutritionistController::class, 'storePatient'])->name('patients.store');
    Route::get('/patients/{patient}', [App\Http\Controllers\NutritionistController::class, 'showPatient'])->name('patients.show');
    Route::get('/nutrition', [App\Http\Controllers\NutritionistController::class, 'nutrition'])->name('nutrition');
    Route::post('/nutrition', [App\Http\Controllers\NutritionistController::class, 'storeNutrition'])->name('nutrition.store');
    Route::get('/nutrition/{nutrition}', [App\Http\Controllers\NutritionistController::class, 'showNutrition'])->name('nutrition.show');
    Route::get('/reports', [App\Http\Controllers\NutritionistController::class, 'reports'])->name('reports');
    // Inventory Management
    Route::get('/inventory', [App\Http\Controllers\NutritionistController::class, 'inventory'])->name('inventory');
    Route::post('/inventory', [App\Http\Controllers\NutritionistController::class, 'storeInventory'])->name('inventory.store');
    Route::get('/inventory/{inventory}', [App\Http\Controllers\NutritionistController::class, 'showInventory'])->name('inventory.show');
    Route::get('/inventory/{inventory}/edit', [App\Http\Controllers\NutritionistController::class, 'editInventory'])->name('inventory.edit');
    Route::put('/inventory/{inventory}', [App\Http\Controllers\NutritionistController::class, 'updateInventory'])->name('inventory.update');
    Route::delete('/inventory/{inventory}', [App\Http\Controllers\NutritionistController::class, 'destroyInventory'])->name('inventory.destroy');
    Route::post('/inventory/transaction', [App\Http\Controllers\NutritionistController::class, 'storeTransaction'])->name('inventory.transaction');
    Route::get('/transactions', [App\Http\Controllers\NutritionistController::class, 'transactions'])->name('transactions');
    Route::post('/transactions', [App\Http\Controllers\NutritionistController::class, 'storeTransaction'])->name('transactions.store');
    Route::get('/transactions/log', [App\Http\Controllers\NutritionistController::class, 'inventoryLog'])->name('transactions.log');
    
    // Treatment Model API Interface
    Route::get('/treatment-model', function () {
        return view('nutritionist.treatment-model');
    })->name('treatment-model');
});

// Nutritionist Application
Route::get('/apply-nutritionist', [App\Http\Controllers\NutritionistApplicationController::class, 'showForm'])->name('nutritionist.apply');
Route::post('/apply-nutritionist', [App\Http\Controllers\NutritionistApplicationController::class, 'submitForm'])->name('nutritionist.apply.submit');

// Admin Nutritionist Approval
Route::post('/admin/users/{user}/approve', [App\Http\Controllers\AdminController::class, 'approveUser'])->name('admin.users.approve');
Route::post('/admin/users/{user}/reject', [App\Http\Controllers\AdminController::class, 'rejectUser'])->name('admin.users.reject');

Route::middleware(['auth', 'parents'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ProfileController::class, 'dashboard'])->name('dashboard');
});

require __DIR__.'/auth.php';
