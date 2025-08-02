<?php

use App\Http\Controllers\ProfileController;
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
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
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

    // API Test Interface
    Route::get('/api-test', function () {
        return view('admin.api-test');
    })->name('api-test');
});

// Nutritionist Routes
Route::middleware(['auth', 'nutritionist'])->prefix('nutritionist')->name('nutritionist.')->group(function () {
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
});

Route::middleware(['auth', 'parents'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ProfileController::class, 'dashboard'])->name('dashboard');
});

require __DIR__.'/auth.php';
