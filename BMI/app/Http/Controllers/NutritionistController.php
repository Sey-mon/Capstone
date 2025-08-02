<?php

namespace App\Http\Controllers;

use App\Models\NutritionAssessment;
use App\Models\Patient;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NutritionistController extends AdminController
{
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
     * Show all patients (specific to nutritionist permissions)
     */
    public function patients()
    {
        $patients = Patient::with('latestAssessment')->get();
        
        return view('nutritionist.patients', [
            'patients' => $patients
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
     * Store a new nutrition assessment
     */
    public function storeNutrition(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'muac' => 'nullable|numeric',
            'nutrition_status' => 'required|in:normal,mild_malnutrition,moderate_malnutrition,severe_malnutrition',
            'clinical_signs' => 'nullable|string',
            'next_assessment_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);
        
        // Calculate BMI
        $weight = $validated['weight'];
        $height = $validated['height'] / 100; // Convert cm to m
        $bmi = $weight / ($height * $height);
        $validated['bmi'] = round($bmi, 2);
        
        // Create assessment
        NutritionAssessment::create($validated);
        
        return redirect()->route('nutritionist.nutrition')->with('success', 'Nutrition assessment added successfully');
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
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:255',
            'medical_history' => 'nullable|string'
        ]);
        
        // Create patient
        Patient::create($validated);
        
        return redirect()->route('nutritionist.patients')->with('success', 'Patient added successfully');
    }

    /**
     * Show inventory management
     */
    public function inventory(Request $request)
    {
        $query = InventoryItem::query();
        $query->where('barangay', Auth::user()->barangay);
        $barangays = InventoryItem::distinct()->pluck('barangay');
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%")
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
        return view('nutritionist.inventory', compact(
            'inventoryItems', 
            'totalItems', 
            'inStockItems', 
            'lowStockItems', 
            'outOfStockItems', 
            'expiringSoon',
            'barangays'
        ));
    }

    /**
     * Show a specific inventory item
     */
    public function showInventory(InventoryItem $inventory)
    {
        $inventory->load('transactions.user');
        return view('nutritionist.inventory.show', compact('inventory'));
    }

    /**
     * Show edit form for a specific inventory item
     */
    public function editInventory(InventoryItem $inventory)
    {
        return view('nutritionist.inventory.edit', compact('inventory'));
    }

    /**
     * Update a specific inventory item
     */
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
        return redirect()->route('nutritionist.inventory.show', $inventory)->with('success', 'Item updated successfully.');
    }

    /**
     * Destroy a specific inventory item
     */
    public function destroyInventory(InventoryItem $inventory)
    {
        $inventory->delete();
        return redirect()->route('nutritionist.inventory')->with('success', 'Item deleted successfully.');
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
            $newStock = max(0, $item->current_stock - $validated['quantity']);
        }
        
        $data['stock_after'] = $newStock;
        
        // Update item stock
        $item->update(['current_stock' => $newStock]);
        
        InventoryTransaction::create($data);

        return redirect()->route('nutritionist.transactions')->with('success', 'Transaction recorded successfully.');
    }

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
