<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\PatientController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Malnutrition Assessment API - Public endpoints
Route::prefix('malnutrition')->group(function () {
    Route::post('/assess', [AssessmentController::class, 'assess']);
    Route::post('/assess/batch', [AssessmentController::class, 'batchAssess']);
    Route::post('/assess/upload', [AssessmentController::class, 'uploadFileAssess']);
    Route::get('/health', [AssessmentController::class, 'healthCheck']);
    Route::get('/protocols', [AssessmentController::class, 'getProtocols']);
    Route::get('/model-info', [AssessmentController::class, 'getModelInfo']);
    Route::post('/uncertainty', [AssessmentController::class, 'getUncertaintyQuantification']);
    Route::post('/recommendations/personalized', [AssessmentController::class, 'getPersonalizedRecommendations']);
    Route::post('/data/validate', [AssessmentController::class, 'validatePatientData']);
    Route::get('/analytics/summary', [AssessmentController::class, 'getAnalyticsSummary']);
});

// Legacy endpoints for backward compatibility
Route::get('/health', [AssessmentController::class, 'healthCheck']);
Route::post('/assess', [AssessmentController::class, 'assess']);
Route::get('/model-info', [AssessmentController::class, 'getModelInfo']);

// Protected API Routes
Route::middleware('auth:sanctum')->group(function () {
    // Patient Management
    Route::apiResource('patients', PatientController::class);
    Route::get('patients/{patient}/nutrition-assessments', [PatientController::class, 'getNutritionAssessments']);
    Route::get('patients/{patient}/treatments', [PatientController::class, 'getTreatments']);
    
    // Nutrition Assessment Management (using existing table)
    Route::apiResource('nutrition-assessments', AssessmentController::class);
    Route::post('nutrition-assessments/{assessment}/recommendations', [AssessmentController::class, 'createRecommendation']);
    
    // Treatment Management
    Route::apiResource('treatments', TreatmentController::class);
    Route::patch('treatments/{treatment}/status', [TreatmentController::class, 'updateStatus']);
    Route::post('treatments/{treatment}/progress', [TreatmentController::class, 'addProgress']);
    
    // Reporting and Analytics
    Route::prefix('reports')->group(function () {
        Route::get('/dashboard', [AssessmentController::class, 'getDashboardData']);
        Route::get('/malnutrition-trends', [AssessmentController::class, 'getMalnutritionTrends']);
        Route::get('/treatment-outcomes', [TreatmentController::class, 'getTreatmentOutcomes']);
        Route::get('/patient-statistics', [PatientController::class, 'getPatientStatistics']);
    });
    
    // Inventory Management (using existing inventory system)
    Route::prefix('inventory')->group(function () {
        Route::get('/nutrition-supplies', [TreatmentController::class, 'getNutritionSupplies']);
        Route::post('/nutrition-supplies/consume', [TreatmentController::class, 'consumeSupplies']);
    });
});