<?php

namespace App\Http\Controllers;

use App\Models\NutritionAssessment;
use App\Models\Patient;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\User;
use App\Services\NutritionAssessmentApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NutritionistController extends AdminController
{
    protected $nutritionApiService;

    public function __construct(NutritionAssessmentApiService $nutritionApiService)
    {
        $this->nutritionApiService = $nutritionApiService;
    }
    /**
     * Show the nutritionist dashboard.
     */
    public function dashboard()
    {
        // Get total patients count
        $totalPatients = Patient::count();
        
        // Get total assessments count
        $totalAssessments = NutritionAssessment::count();
        
        // Get patients with critical nutrition status
        $criticalCases = NutritionAssessment::where('nutrition_status', 'severe_malnutrition')
            ->orderBy('assessment_date', 'desc')
            ->with('patient')
            ->take(5)
            ->get();
        
        // Get nutrition status distribution
        $nutritionStats = [
            'normal' => NutritionAssessment::where('nutrition_status', 'normal')->count(),
            'mild_malnutrition' => NutritionAssessment::where('nutrition_status', 'mild_malnutrition')->count(),
            'moderate_malnutrition' => NutritionAssessment::where('nutrition_status', 'moderate_malnutrition')->count(),
            'severe_malnutrition' => NutritionAssessment::where('nutrition_status', 'severe_malnutrition')->count(),
        ];
        
        // Get assessments needing follow-up
        $followupNeeded = NutritionAssessment::whereDate('next_assessment_date', '<=', now()->addDays(7))
            ->with('patient')
            ->take(5)
            ->get();

        // Inventory statistics
        $totalInventoryItems = InventoryItem::count();
        $lowStockItems = InventoryItem::whereRaw('current_stock <= minimum_stock')->count();
        $outOfStockItems = InventoryItem::where('current_stock', '<=', 0)->count();
        $expiringSoon = InventoryItem::where('expiry_date', '<=', now()->addDays(30))
            ->whereNotNull('expiry_date')
            ->count();
            
        return view('nutritionist.dashboard', [
            'totalPatients' => $totalPatients,
            'totalAssessments' => $totalAssessments,
            'criticalCases' => $criticalCases,
            'nutritionStats' => $nutritionStats,
            'followupNeeded' => $followupNeeded,
            'totalInventoryItems' => $totalInventoryItems,
            'lowStockItems' => $lowStockItems,
            'outOfStockItems' => $outOfStockItems,
            'expiringSoon' => $expiringSoon,
        ]);
    }
    
/**
 * Show patients view
 */
public function patients(Request $request)
{
    $barangays = \App\Models\Barangay::orderBy('name')->pluck('name', 'id');
    $selectedBarangay = $request->get('barangay');
    $query = Patient::with('latestAssessment');
    if ($selectedBarangay) {
        $query->where('barangay_id', $selectedBarangay);
    }
    $patients = $query->get();
    return view('nutritionist.patients', [
        'patients' => $patients,
        'barangays' => $barangays,
        'selectedBarangay' => $selectedBarangay,
    ]);
}
    
    /**
     * Show nutrition assessments view
     */
    public function nutrition()
    {
        $assessments = NutritionAssessment::with('patient')->get();
        $patients = Patient::all();
        
        return view('nutritionist.nutrition', [
            'assessments' => $assessments,
            'patients' => $patients
        ]);
    }
    
    /**
     * Store a new nutrition assessment with API integration
     */
    public function storeNutrition(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'weight' => 'required|numeric|min:0.1',
            'height' => 'required|numeric|min:10',
            'edema' => 'boolean',
            'clinical_signs' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'dietary_intake' => 'nullable|string',
            'feeding_practices' => 'nullable|string',
            'appetite' => 'nullable|in:poor,fair,good',
            'vomiting' => 'boolean',
            'diarrhea' => 'boolean',
            'recommendations' => 'nullable|string',
            'next_assessment_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string'
        ]);

        // Get patient information
        $patient = Patient::find($validated['patient_id']);
        
        // Calculate BMI
        $weight = $validated['weight'];
        $height = $validated['height'];
        $heightInMeters = $height / 100; // Convert cm to m
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        $validated['bmi'] = round($bmi, 2);

        // Get API assessment using age in months from patient record
        $hasEdema = $validated['edema'] ?? false;
        $apiAssessment = $this->nutritionApiService->assessChild(
            $validated['weight'],
            $validated['height'],
            $patient->age_months, // Use age_months from patient record
            $patient->sex, // Use sex from patient record
            $hasEdema
        );

        // Add API results to validated data
        $validated = array_merge($validated, [
            'assessed_by' => Auth::id(),
            'assessment_date' => now(),
            'whz_score' => $apiAssessment['whz_score'],
            'waz_score' => $apiAssessment['waz_score'],
            'haz_score' => $apiAssessment['haz_score'],
            'confidence_score' => $apiAssessment['confidence_score'],
            'api_response' => $apiAssessment['api_response'],
            'model_version' => $apiAssessment['model_version'],
            'assessment_method' => $apiAssessment['assessment_method'],
            'edema' => $hasEdema,
            'vomiting' => $validated['vomiting'] ?? false,
            'diarrhea' => $validated['diarrhea'] ?? false,
            'follow_up_required' => $this->determineFollowUpRequired($apiAssessment['overall_assessment'])
        ]);

        // Determine nutrition status based on API assessment
        $validated['nutrition_status'] = $apiAssessment['overall_assessment'];

        // Create assessment
        $assessment = NutritionAssessment::create($validated);

        $message = 'Nutrition assessment added successfully with Z-score analysis';
        if (!($apiAssessment['api_available'] ?? true)) {
            $message .= ' (API unavailable - using fallback calculation)';
        }

        return redirect()->route('nutritionist.nutrition')->with('success', $message);
    }

    /**
     * Determine if follow-up is required based on assessment
     */
    private function determineFollowUpRequired($overallAssessment)
    {
        return in_array($overallAssessment, [
            'severe_malnutrition',
            'moderate_malnutrition',
            'mild_malnutrition'
        ]);
    }
    
    /**
     * Show a specific assessment
     */
    public function showNutrition(NutritionAssessment $nutrition)
    {
        return view('nutritionist.nutrition-details', [
            'assessment' => $nutrition->load('patient')
        ]);
    }
    
    /**
     * Show reports view
     */
    public function reports()
    {
        // Get critical cases
        $criticalCases = NutritionAssessment::where('nutrition_status', 'severe_malnutrition')
            ->with('patient')
            ->take(10)
            ->get();
            
        // Get nutrition status distribution
        $nutritionStats = [
            'normal' => NutritionAssessment::where('nutrition_status', 'normal')->count(),
            'mild_malnutrition' => NutritionAssessment::where('nutrition_status', 'mild_malnutrition')->count(),
            'moderate_malnutrition' => NutritionAssessment::where('nutrition_status', 'moderate_malnutrition')->count(),
            'severe_malnutrition' => NutritionAssessment::where('nutrition_status', 'severe_malnutrition')->count(),
        ];
        
        $totalAssessments = NutritionAssessment::count();
        
        return view('nutritionist.reports', [
            'criticalCases' => $criticalCases,
            'nutritionStats' => $nutritionStats,
            'totalAssessments' => $totalAssessments,
        ]);
    }
    
    /**
     * Show a specific patient record
     */
    public function showPatient(Patient $patient)
    {
        $assessments = $patient->nutritionAssessments()->orderBy('assessment_date', 'desc')->get();
        
        return view('nutritionist.patient-details', [
            'patient' => $patient,
            'assessments' => $assessments
        ]);
    }
    
    /**
     * Store a new patient
     */
    public function storePatient(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string',
            'barangay' => 'nullable|string',
            'contact_number' => 'nullable|string',
        ]);
        
        // Create patient
        Patient::create($validated);
        return redirect()->route('nutritionist.patients')->with('success', 'Patient added successfully');
    }
    
    /**
     * Store a new inventory item
     */
    public function storeInventory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:inventory_items',
            'category' => 'required|in:supplements,food,medicine,equipment,other',
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'description' => 'nullable|string'
        ]);
        $data = $request->all();
        $data['barangay'] = Auth::user()->barangay;
        InventoryItem::create($data);
        return redirect()->route('nutritionist.inventory')->with('success', 'Item added successfully.');
    }

    /**
     * Show inventory transactions
     */
    public function transactions()
    {
        $transactions = InventoryTransaction::with(['inventoryItem', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $inventoryItems = InventoryItem::all();
        $users = User::all();

        $totalTransactions = InventoryTransaction::count();
        $todayStockIn = InventoryTransaction::where('type', 'in')
            ->whereDate('created_at', today())
            ->sum('quantity');
        $todayStockOut = InventoryTransaction::where('type', 'out')
            ->whereDate('created_at', today())
            ->sum('quantity');
        $thisWeekTransactions = InventoryTransaction::where('created_at', '>=', now()->startOfWeek())
            ->count();

        return view('nutritionist.transactions', compact(
            'transactions', 
            'inventoryItems', 
            'users', 
            'totalTransactions', 
            'todayStockIn', 
            'todayStockOut', 
            'thisWeekTransactions'
        ));
    }

    /**
     * Store a new transaction
     */
    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:1',
            'notes' => 'nullable|string'
        ]);

        $item = InventoryItem::find($validated['inventory_item_id']);
        
        $data = $validated;
        $data['user_id'] = Auth::id();
        $data['stock_before'] = $item->current_stock;
        
        if ($validated['type'] === 'in') {
            $newStock = $item->current_stock + $validated['quantity'];
        } else {
            $newStock = $item->current_stock - $validated['quantity'];
        }
        
        $item->update(['current_stock' => $newStock]);
        
        InventoryTransaction::create($data);
        
        return redirect()->route('nutritionist.transactions')->with('success', 'Transaction recorded successfully.');
    }

    /**
     * Show inventory log
     */
    public function inventoryLog(Request $request)
    {
        $transactions = InventoryTransaction::with(['inventoryItem', 'user'])
            ->whereHas('inventoryItem', function($q) {
                $q->where('barangay', Auth::user()->barangay);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('nutritionist.inventory-log', compact('transactions'));
    }
}
