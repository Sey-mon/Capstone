<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use App\Models\NutritionAssessment;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\Barangay;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware will be applied in routes
    }
    /**
     * Show the admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_patients' => Patient::count(),
            'total_assessments' => NutritionAssessment::count(),
            'malnourished_patients' => Patient::whereHas('nutritionAssessments', function($q) {
                $q->where('nutrition_status', 'severe_malnutrition');
            })->count(),
            'total_inventory_items' => InventoryItem::count(),
            'low_stock_items' => InventoryItem::whereRaw('current_stock <= minimum_stock')->count(),
            'recent_assessments' => NutritionAssessment::where('created_at', '>=', now()->subDays(7))->count(),
            'follow_ups_due' => NutritionAssessment::where('next_assessment_date', '<=', now()->addDays(7))->count(),
        ];

        $recent_patients = Patient::with('nutritionAssessments')->latest()->take(5)->get();
        $critical_cases = NutritionAssessment::with('patient')
            ->where('nutrition_status', 'severe_malnutrition')
            ->latest()
            ->take(5)
            ->get();
        
        $low_stock_items = InventoryItem::whereRaw('current_stock <= minimum_stock')
            ->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_patients', 'critical_cases', 'low_stock_items'));
    }

    /**
     * Show users management
     */
    public function users()
    {
        $users = User::paginate(15);
        return view('admin.users', compact('users'));
    }

    public function approveUser(User $user)
    {
        if ($user->role === 'nutritionist' && $user->status === 'pending') {
            $user->status = 'approved';
            $user->save();
            
            // Send approval notification email
            $user->notify(new \App\Notifications\NutritionistApprovedNotification());
            
            return redirect()->back()->with('success', 'Nutritionist approved successfully.');
        }
        
        return redirect()->back()->with('error', 'Invalid user or status.');
    }

    public function rejectUser(User $user)
    {
        if ($user->role === 'nutritionist' && $user->status === 'pending') {
            $user->status = 'rejected';
            $user->save();
            
            // Optionally: Send rejection notification email
            return redirect()->back()->with('success', 'Nutritionist rejected.');
        }
        
        return redirect()->back()->with('error', 'Invalid user or status.');
    }

    /**
     * Show patients management
     */
    public function patients(Request $request)
    {
        $query = Patient::with(['lastAssessment', 'barangay']);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
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
        
        if ($request->filled('status')) {
            $query->where('admission_status', $request->status);
        }
        
        // New filter: Nutrition Status
        if ($request->filled('nutrition')) {
            if ($request->nutrition === 'not_assessed') {
                $query->whereDoesntHave('nutritionAssessments');
            } else {
                $query->whereHas('nutritionAssessments', function($q) use ($request) {
                    $q->where('nutrition_status', $request->nutrition);
                });
            }
        }
        
        // New filter: Age Group
        if ($request->filled('age_group')) {
            $ageRange = explode('-', $request->age_group);
            if (count($ageRange) === 2) {
                $minMonths = (int)$ageRange[0];
                $maxMonths = (int)$ageRange[1];
                $query->whereBetween('age_months', [$minMonths, $maxMonths]);
            } elseif ($request->age_group === '60+') {
                $query->where('age_months', '>=', 60);
            }
        }
        
        // New filter: Medical Conditions
        if ($request->filled('medical')) {
            switch ($request->medical) {
                case 'tuberculosis':
                    $query->where('has_tuberculosis', true);
                    break;
                case 'malaria':
                    $query->where('has_malaria', true);
                    break;
                case 'congenital':
                    $query->where('has_congenital_anomalies', true);
                    break;
                case 'edema':
                    $query->where('has_edema', true);
                    break;
                case 'breastfeeding':
                    $query->where('is_breastfeeding', true);
                    break;
                case '4ps':
                    $query->where('is_4ps_beneficiary', true);
                    break;
            }
        }
        
        // New filter: Gender
        if ($request->filled('gender')) {
            $query->where('sex', $request->gender);
        }
        
        $patients = $query->paginate(15);
        
        // Debug: Check what barangays are in the patients table
        $patientBarangays = Patient::distinct()->pluck('barangay')->filter()->values();
        $barangayIds = Patient::distinct()->pluck('barangay_id')->filter()->values();
        
        $totalPatients = Patient::count();
        $malnourishedPatients = Patient::whereHas('nutritionAssessments', function($q) {
            $q->where('nutrition_status', 'severe_malnutrition');
        })->count();
        $atRiskPatients = Patient::whereHas('nutritionAssessments', function($q) {
            $q->where('nutrition_status', 'moderate_malnutrition');
        })->count();
        $normalPatients = Patient::whereHas('nutritionAssessments', function($q) {
            $q->where('nutrition_status', 'normal');
        })->count();

        // Get barangays from barangays table for filter dropdown
        $barangays = Barangay::orderBy('name')->pluck('name');

        return view('admin.patients', compact(
            'patients', 
            'totalPatients', 
            'malnourishedPatients', 
            'atRiskPatients', 
            'normalPatients',
            'barangays'
        ));
    }

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

        return redirect()->route('admin.patients')->with('success', 'Patient added successfully.');
    }

    public function showPatient(Patient $patient)
    {
        $patient->load(['nutritionAssessments' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show nutrition assessments
     */
    public function nutrition()
    {
        $assessments = NutritionAssessment::with('patient')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $patients = Patient::all();

        $totalAssessments = NutritionAssessment::count();
        $thisMonthAssessments = NutritionAssessment::whereMonth('created_at', now()->month)->count();
        $followUpsDue = NutritionAssessment::where('next_assessment_date', '<=', now()->addDays(7))
            ->whereNotNull('next_assessment_date')
            ->count();
        $criticalCases = NutritionAssessment::where('nutrition_status', 'severe_malnutrition')->count();

        return view('admin.nutrition', compact(
            'assessments', 
            'patients', 
            'totalAssessments', 
            'thisMonthAssessments', 
            'followUpsDue', 
            'criticalCases'
        ));
    }

    public function storeNutrition(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'height' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'temperature' => 'nullable|numeric|min:0',
            'clinical_signs' => 'nullable|array',
            'notes' => 'nullable|string',
            'next_assessment_date' => 'nullable|date|after:today'
        ]);

        $data = $request->all();
        
        // Calculate BMI
        $height_m = $data['height'] / 100;
        $data['bmi'] = $data['weight'] / ($height_m * $height_m);
        
        // Determine BMI category
        $bmi = $data['bmi'];
        if ($bmi < 18.5) {
            $data['bmi_category'] = 'underweight';
        } elseif ($bmi < 25) {
            $data['bmi_category'] = 'normal';
        } elseif ($bmi < 30) {
            $data['bmi_category'] = 'overweight';
        } else {
            $data['bmi_category'] = 'obese';
        }

        // Determine nutritional status (simplified logic)
        if ($bmi < 16) {
            $data['nutrition_status'] = 'severe_malnutrition';
        } elseif ($bmi < 18.5) {
            $data['nutrition_status'] = 'moderate_malnutrition';
        } else {
            $data['nutrition_status'] = 'normal';
        }

        // Convert clinical signs array to JSON
        if (isset($data['clinical_signs'])) {
            $data['clinical_signs'] = json_encode($data['clinical_signs']);
        }

        $data['user_id'] = Auth::id();

        NutritionAssessment::create($data);

        return redirect()->route('admin.nutrition')->with('success', 'Assessment saved successfully.');
    }

    public function showNutrition(NutritionAssessment $nutrition)
    {
        $nutrition->load('patient', 'user');
        return view('admin.nutrition.show', compact('nutrition'));
    }

    /**
     * Show inventory management
     */
    public function inventory(Request $request)
    {
        $query = InventoryItem::query();
        // Get barangays from facilities instead of inventory items
        $barangays = \App\Models\Facility::with('barangay')
            ->get()
            ->pluck('barangay.name')
            ->filter()
            ->unique()
            ->values();
        $selectedBarangay = $request->get('barangay');
        if ($selectedBarangay) {
            $query->whereHas('facility.barangay', function($q) use ($selectedBarangay) {
                $q->where('name', $selectedBarangay);
            });
        }
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('sku', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ;
            });
        }
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }
        if ($request->filled('unit')) {
            $query->where('unit', $request->get('unit'));
        }
        if ($request->filled('stock_status')) {
            if ($request->get('stock_status') === 'in_stock') {
                $query->where('current_stock', '>', 0)->whereColumn('current_stock', '>', 'minimum_stock');
            } elseif ($request->get('stock_status') === 'low_stock') {
                $query->whereColumn('current_stock', '<=', 'minimum_stock')->where('current_stock', '>', 0);
            } elseif ($request->get('stock_status') === 'out_of_stock') {
                $query->where('current_stock', '<=', 0);
            }
        }
        $inventoryItems = $query->paginate(15);
        $totalItems = $inventoryItems->total();
        $inStockItems = $inventoryItems->where('current_stock', '>', 0)->count();
        $lowStockItems = $inventoryItems->where('current_stock', '<=', 'minimum_stock')->where('current_stock', '>', 0)->count();
        $outOfStockItems = $inventoryItems->where('current_stock', '<=', 0)->count();
        $expiringSoon = $inventoryItems->where('expiry_date', '<=', now()->addDays(30))->whereNotNull('expiry_date');
        return view('admin.inventory', compact(
            'inventoryItems', 
            'totalItems', 
            'inStockItems', 
            'lowStockItems', 
            'outOfStockItems', 
            'expiringSoon',
            'barangays',
            'selectedBarangay'
        ));
    }

    public function storeInventory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'facility_id' => 'nullable|exists:facilities,id',
            'sku' => 'required|string|max:50|unique:inventory_items',
            'category' => 'required|in:therapeutic_food,supplements,medical_supplies,equipment,medications,medicine,other',
            'unit' => 'required|in:pieces,boxes,packets,bottles,kg,grams,liters,ml,doses,vials',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'description' => 'nullable|string'
        ]);
        InventoryItem::create($request->all());
        return redirect()->route('admin.inventory')->with('success', 'Item added successfully.');
    }

    public function showInventory(InventoryItem $inventory)
    {
        $inventory->load('transactions.user');
        return view('admin.inventory.show', compact('inventory'));
    }

    public function editInventory(InventoryItem $inventory)
    {
        return view('admin.inventory.edit', compact('inventory'));
    }

    public function updateInventory(Request $request, InventoryItem $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:inventory_items,code,' . $inventory->id,
            'category' => 'required|in:supplements,food,medicine,equipment,other',
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'description' => 'nullable|string'
        ]);
        $inventory->update($request->all());
        return redirect()->route('admin.inventory.show', $inventory)->with('success', 'Item updated successfully.');
    }

    public function destroyInventory(InventoryItem $inventory)
    {
        $inventory->delete();
        return redirect()->route('admin.inventory')->with('success', 'Item deleted successfully.');
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:1',
            'notes' => 'nullable|string'
        ]);

        $item = InventoryItem::find($request->inventory_item_id);
        
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['stock_before'] = $item->current_stock;
        
        if ($request->type === 'in') {
            $newStock = $item->current_stock + $request->quantity;
        } else {
            $newStock = max(0, $item->current_stock - $request->quantity);
        }
        
        $data['stock_after'] = $newStock;
        
        // Update item stock
        $item->update(['current_stock' => $newStock]);
        
        InventoryTransaction::create($data);

        return redirect()->route('admin.transactions')->with('success', 'Transaction recorded successfully.');
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

        return view('admin.transactions', compact(
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
     * Show reports
     */
    public function reports()
    {
        // Nutrition Statistics
        $totalAssessments = NutritionAssessment::count();
        $nutritionStats = [];
        if ($totalAssessments > 0) {
            $nutritionStats = [
                'normal' => round((NutritionAssessment::where('nutrition_status', 'normal')->count() / $totalAssessments) * 100, 1),
                'mild' => round((NutritionAssessment::where('nutrition_status', 'mild_malnutrition')->count() / $totalAssessments) * 100, 1),
                'moderate' => round((NutritionAssessment::where('nutrition_status', 'moderate_malnutrition')->count() / $totalAssessments) * 100, 1),
                'severe' => round((NutritionAssessment::where('nutrition_status', 'severe_malnutrition')->count() / $totalAssessments) * 100, 1),
            ];
        }

        // Inventory Statistics
        $inventoryStats = [
            'total_value' => InventoryItem::sum('current_stock') * 10, // Simplified calculation
            'movements' => InventoryTransaction::count(),
            'low_stock' => InventoryItem::whereRaw('current_stock <= minimum_stock')->count(),
            'expiring' => InventoryItem::where('expiry_date', '<=', now()->addDays(30))->whereNotNull('expiry_date')->count(),
        ];

        // Critical Cases
        $criticalCases = NutritionAssessment::with('patient')
            ->where('nutrition_status', 'severe_malnutrition')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports', compact(
            'nutritionStats', 
            'inventoryStats', 
            'criticalCases', 
            'totalAssessments'
        ));
    }
}
