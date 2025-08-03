<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use App\Models\NutritionAssessment;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminControllerNew extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_patients' => Patient::count(),
            'active_patients' => Patient::where('status', 'active')->count(),
            'high_risk_patients' => Patient::whereHas('latestAssessment', function($query) {
                $query->whereIn('risk_level', ['high', 'critical']);
            })->count(),
            'recent_assessments' => NutritionAssessment::where('assessment_date', '>=', now()->subDays(7))->count(),
            'low_stock_items' => InventoryItem::whereRaw('current_stock <= minimum_stock')->count(),
            'expired_items' => InventoryItem::where('expiry_date', '<', now())->count(),
        ];

        $recent_patients = Patient::with('latestAssessment')->latest()->take(5)->get();
        $critical_patients = Patient::whereHas('latestAssessment', function($query) {
            $query->where('risk_level', 'critical');
        })->with('latestAssessment')->take(5)->get();
        
        $low_stock_items = InventoryItem::whereRaw('current_stock <= minimum_stock')
            ->where('status', 'active')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_patients', 'critical_patients', 'low_stock_items'));
    }

    /**
     * Show users management
     */
    public function users()
    {
        $users = User::paginate(15);
        return view('admin.users', compact('users'));
    }

    /**
     * Store a new user
     */
    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    /**
     * Update a user
     */
    public function userUpdate(Request $request, User $user)
    {
        $userId = $user->getKey();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'role' => 'required|in:admin,user',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Delete a user
     */
    public function userDestroy(User $user)
    {
        if ($user->getKey() === Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    /**
     * Show patients management
     */
    public function patients()
    {
        $patients = Patient::with('latestAssessment')->paginate(15);
        return view('admin.patients', compact('patients'));
    }

    /**
     * Show patient details
     */
    public function patientShow($id)
    {
        $patient = Patient::with(['nutritionAssessments.assessor'])->findOrFail($id);
        return view('admin.patient-details', compact('patient'));
    }

    /**
     * Store new patient
     */
    public function patientStore(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:20',
            'medical_history' => 'nullable|string',
        ]);

        Patient::create($validated);
        return redirect()->route('admin.patients')->with('success', 'Patient created successfully');
    }

    /**
     * Show nutrition assessments
     */
    public function nutritionAssessments()
    {
        $assessments = NutritionAssessment::with(['patient', 'assessor'])
            ->orderBy('assessment_date', 'desc')->paginate(15);
        return view('admin.nutrition-assessments', compact('assessments'));
    }

    /**
     * Store nutrition assessment
     */
    public function assessmentStore(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'assessment_date' => 'required|date',
            'weight' => 'required|numeric|min:0|max:500',
            'height' => 'required|numeric|min:0|max:300',
            'nutrition_status' => 'required|in:normal,mild_malnutrition,moderate_malnutrition,severe_malnutrition',
            'risk_level' => 'required|in:low,medium,high,critical',
            'symptoms' => 'nullable|string',
            'dietary_intake' => 'nullable|string',
            'clinical_signs' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'next_assessment_date' => 'nullable|date',
        ]);

        // Calculate BMI
        $validated['bmi'] = NutritionAssessment::calculateBMI($validated['weight'], $validated['height']);
        $validated['assessed_by'] = Auth::id();

        NutritionAssessment::create($validated);
        return redirect()->back()->with('success', 'Assessment recorded successfully');
    }

    /**
     * Show inventory management
     */
    public function inventory()
    {
        $items = InventoryItem::paginate(15);
        $categories = ['therapeutic_food', 'supplements', 'medical_supplies', 'equipment', 'medications', 'other'];
        $units = ['pieces', 'boxes', 'packets', 'bottles', 'kg', 'liters'];
        
        return view('admin.inventory', compact('items', 'categories', 'units'));
    }

    /**
     * Store inventory item
     */
    public function inventoryStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:inventory_items',
            'description' => 'nullable|string',
            'category' => 'required|in:therapeutic_food,supplements,medical_supplies,equipment,medications,other',
            'unit' => 'required|in:pieces,boxes,packets,bottles,kg,liters',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'maximum_stock' => 'required|integer|min:1',
            'unit_cost' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'supplier' => 'nullable|string|max:255',
        ]);

        InventoryItem::create($validated);
        return redirect()->route('admin.inventory')->with('success', 'Item added successfully');
    }

    /**
     * Update inventory stock
     */
    public function inventoryUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:stock_in,stock_out,adjustment',
            'quantity' => 'required|integer',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $item = InventoryItem::findOrFail($id);
        $previousStock = $item->current_stock;

        switch ($validated['type']) {
            case 'stock_in':
                $newStock = $previousStock + $validated['quantity'];
                break;
            case 'stock_out':
                $newStock = max(0, $previousStock - $validated['quantity']);
                break;
            case 'adjustment':
                $newStock = $validated['quantity'];
                break;
        }

        // Update item stock
        $item->update(['current_stock' => $newStock]);

        // Record transaction
        InventoryTransaction::create([
            'inventory_item_id' => $item->id,
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
            'transaction_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Stock updated successfully');
    }

    /**
     * Show inventory transactions
     */
    public function inventoryTransactions()
    {
        $transactions = InventoryTransaction::with(['inventoryItem', 'user'])
            ->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.inventory-transactions', compact('transactions'));
    }

    /**
     * Show reports
     */
    public function reports()
    {
        $malnutritionStats = [
            'normal' => NutritionAssessment::where('nutrition_status', 'normal')->count(),
            'mild' => NutritionAssessment::where('nutrition_status', 'mild_malnutrition')->count(),
            'moderate' => NutritionAssessment::where('nutrition_status', 'moderate_malnutrition')->count(),
            'severe' => NutritionAssessment::where('nutrition_status', 'severe_malnutrition')->count(),
        ];

        $monthlyAssessments = NutritionAssessment::selectRaw('MONTH(assessment_date) as month, COUNT(*) as count')
            ->whereYear('assessment_date', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');

        $inventoryValue = InventoryItem::whereNotNull('unit_cost')
            ->selectRaw('SUM(current_stock * unit_cost) as total')
            ->first()->total ?? 0;

        // Get critical cases with patient relationship
        $criticalCases = NutritionAssessment::with('patient')
            ->whereIn('nutrition_status', ['severe_malnutrition', 'moderate_malnutrition'])
            ->whereIn('risk_level', ['high', 'critical'])
            ->orderBy('assessment_date', 'desc')
            ->take(10)
            ->get();

        // Calculate additional stats for the view
        $nutritionStats = [
            'normal' => $malnutritionStats['normal'],
            'at_risk' => $malnutritionStats['mild'],
            'malnourished' => $malnutritionStats['moderate'] + $malnutritionStats['severe'],
        ];

        $totalAssessments = array_sum($malnutritionStats);

        $inventoryStats = [
            'total_value' => $inventoryValue,
            'movements' => InventoryTransaction::count(),
            'low_stock' => InventoryItem::whereRaw('current_stock <= minimum_stock')->count(),
            'expiring' => InventoryItem::where('expiry_date', '<=', now()->addDays(30))->count(),
        ];

        return view('admin.reports', compact(
            'malnutritionStats', 
            'monthlyAssessments', 
            'inventoryValue',
            'criticalCases',
            'nutritionStats',
            'totalAssessments',
            'inventoryStats'
        ));
    }
}
