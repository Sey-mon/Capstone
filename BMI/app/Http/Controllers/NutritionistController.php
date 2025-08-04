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
        // Get total patients count (children)
        $totalChildren = Patient::count();
        
        // Get patients by nutrition status
        $atRisk = Patient::whereHas('nutritionAssessments', function($q) {
            $q->whereIn('nutrition_status', ['moderate_malnutrition', 'severe_malnutrition']);
        })->count();
        
        $recovered = Patient::whereHas('nutritionAssessments', function($q) {
            $q->where('nutrition_status', 'normal');
        })->count();
        
        $totalInventory = InventoryItem::count();
        
        // Get nutrition status distribution for charts
        $nutritionStats = [
            'normal' => NutritionAssessment::where('nutrition_status', 'normal')->count(),
            'mild_malnutrition' => NutritionAssessment::where('nutrition_status', 'mild_malnutrition')->count(),
            'moderate_malnutrition' => NutritionAssessment::where('nutrition_status', 'moderate_malnutrition')->count(),
            'severe_malnutrition' => NutritionAssessment::where('nutrition_status', 'severe_malnutrition')->count(),
        ];
        
        // Get monthly trend data for the last 6 months
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M');
            
            $monthlyTrends[$monthName] = [
                'normal' => NutritionAssessment::where('nutrition_status', 'normal')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'mild_malnutrition' => NutritionAssessment::where('nutrition_status', 'mild_malnutrition')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'moderate_malnutrition' => NutritionAssessment::where('nutrition_status', 'moderate_malnutrition')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'severe_malnutrition' => NutritionAssessment::where('nutrition_status', 'severe_malnutrition')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }
        
        // Get critical cases
        $criticalCases = Patient::whereHas('nutritionAssessments', function($q) {
            $q->where('nutrition_status', 'severe_malnutrition');
        })->with(['lastAssessment', 'barangay'])
        ->take(5)
        ->get();
        
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
            'totalChildren' => $totalChildren,
            'atRisk' => $atRisk,
            'recovered' => $recovered,
            'totalInventory' => $totalInventory,
            'nutritionStats' => $nutritionStats,
            'monthlyTrends' => $monthlyTrends,
            'criticalCases' => $criticalCases,
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
    $query = Patient::with(['lastAssessment', 'barangay']);
    
    // Apply filters
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('barangay', 'like', "%{$search}%")
              ->orWhere('municipality', 'like', "%{$search}%");
        });
    }
    
    if ($request->filled('barangay')) {
        $query->where(function($q) use ($request) {
            $q->where('barangay', $request->barangay)
              ->orWhereHas('barangay', function($subQuery) use ($request) {
                  $subQuery->where('name', $request->barangay);
              });
        });
    }
    
    // Filter by nutrition status
    if ($request->filled('nutrition')) {
        if ($request->nutrition === 'not_assessed') {
            $query->whereDoesntHave('nutritionAssessments');
        } else {
            $query->whereHas('nutritionAssessments', function($q) use ($request) {
                $q->where('nutrition_status', $request->nutrition);
            });
        }
    }
    
    // Filter by gender
    if ($request->filled('gender')) {
        $query->where('sex', $request->gender);
    }
    
    $patients = $query->get();
    
    // Get barangays for filter dropdown
    $barangays = \App\Models\Barangay::orderBy('name')->pluck('name');
    $selectedBarangay = $request->get('barangay');
    
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
        $request->validate([
            'name' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'age_months' => 'required|integer|min:0|max:1200',
            'sex' => 'required|in:male,female',
            'date_of_admission' => 'required|date',
            'admission_status' => 'required|in:admitted,discharged,pending',
            'total_household_members' => 'required|integer|min:1',
            'household_adults' => 'required|integer|min:0',
            'household_children' => 'required|integer|min:0',
            'is_twin' => 'boolean',
            'is_4ps_beneficiary' => 'boolean',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'whz_score' => 'nullable|numeric|min:-5|max:5',
            'is_breastfeeding' => 'boolean',
            'has_tuberculosis' => 'boolean',
            'has_malaria' => 'boolean',
            'has_congenital_anomalies' => 'boolean',
            'other_medical_problems' => 'nullable|string|max:1000',
            'has_edema' => 'boolean',
            'religion' => 'required|string|max:255',
        ]);

        $patientData = $request->all();
        
        // Convert checkbox values to boolean
        $patientData['is_twin'] = $request->boolean('is_twin');
        $patientData['is_4ps_beneficiary'] = $request->boolean('is_4ps_beneficiary');
        $patientData['is_breastfeeding'] = $request->boolean('is_breastfeeding');
        $patientData['has_tuberculosis'] = $request->boolean('has_tuberculosis');
        $patientData['has_malaria'] = $request->boolean('has_malaria');
        $patientData['has_congenital_anomalies'] = $request->boolean('has_congenital_anomalies');
        $patientData['has_edema'] = $request->boolean('has_edema');

        Patient::create($patientData);

        return redirect()->route('nutritionist.patients')->with('success', 'Child added successfully.');
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
