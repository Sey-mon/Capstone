<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            // Patient information validation
            'municipality' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'age_months' => ['required', 'integer', 'min:0', 'max:1200'], // 0-100 years in months
            'sex' => ['required', 'in:male,female'],
            'date_of_admission' => ['required', 'date'],
            'admission_status' => ['required', 'in:admitted,discharged,pending'],
            'total_household_members' => ['required', 'integer', 'min:1'],
            'household_adults' => ['required', 'integer', 'min:0'],
            'household_children' => ['required', 'integer', 'min:0'],
            'is_twin' => ['boolean'],
            'is_4ps_beneficiary' => ['boolean'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:500'], // kg
            'height' => ['nullable', 'numeric', 'min:0', 'max:300'], // cm
            'whz_score' => ['nullable', 'numeric', 'min:-5', 'max:5'],
            'is_breastfeeding' => ['boolean'],
            'has_tuberculosis' => ['boolean'],
            'has_malaria' => ['boolean'],
            'has_congenital_anomalies' => ['boolean'],
            'other_medical_problems' => ['nullable', 'string', 'max:1000'],
            'has_edema' => ['boolean'],
            'religion' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'barangay' => $request->barangay,
        ]);

        // Create patient record
        Patient::create([
            'name' => $request->name,
            'municipality' => $request->municipality,
            'barangay' => $request->barangay,
            'age_months' => $request->age_months,
            'sex' => $request->sex,
            'date_of_admission' => $request->date_of_admission,
            'admission_status' => $request->admission_status,
            'total_household_members' => $request->total_household_members,
            'household_adults' => $request->household_adults,
            'household_children' => $request->household_children,
            'is_twin' => $request->boolean('is_twin'),
            'is_4ps_beneficiary' => $request->boolean('is_4ps_beneficiary'),
            'weight' => $request->weight,
            'height' => $request->height,
            'whz_score' => $request->whz_score,
            'is_breastfeeding' => $request->boolean('is_breastfeeding'),
            'has_tuberculosis' => $request->boolean('has_tuberculosis'),
            'has_malaria' => $request->boolean('has_malaria'),
            'has_congenital_anomalies' => $request->boolean('has_congenital_anomalies'),
            'other_medical_problems' => $request->other_medical_problems,
            'has_edema' => $request->boolean('has_edema'),
            'religion' => $request->religion,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
