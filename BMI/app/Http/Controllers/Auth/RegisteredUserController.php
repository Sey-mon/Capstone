<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Barangay;
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
        $barangays = Barangay::orderBy('name')->get();
        return view('auth.register', compact('barangays'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'sex' => ['required', 'in:male,female'],
            'barangay_id' => ['required', 'exists:barangays,id'],
            'address' => ['nullable', 'string', 'max:500'],
            'total_household_members' => ['required', 'integer', 'min:1', 'max:50'],
            'household_adults' => ['required', 'integer', 'min:1', 'max:50'],
            'household_children' => ['required', 'integer', 'min:0', 'max:50'],
            'is_twin' => ['boolean'],
            'is_4ps_beneficiary' => ['boolean'],
            'terms_accepted' => ['required', 'accepted'],
        ]);

        // Validate household numbers
        if ($request->household_adults + $request->household_children != $request->total_household_members) {
            return back()->withErrors([
                'total_household_members' => 'Total household members must equal the sum of adults and children.'
            ])->withInput();
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'date_of_birth' => $request->date_of_birth,
            'sex' => $request->sex,
            'barangay_id' => $request->barangay_id,
            'address' => $request->address,
            'role' => 'parent_guardian',
            'status' => 'email_pending',
            'is_active' => true,
            'email_verification_attempts' => 0,
        ]);

        // Store additional household information in session for patient creation
        $request->session()->put('household_info', [
            'total_household_members' => $request->total_household_members,
            'household_adults' => $request->household_adults,
            'household_children' => $request->household_children,
            'is_twin' => $request->boolean('is_twin'),
            'is_4ps_beneficiary' => $request->boolean('is_4ps_beneficiary'),
        ]);

        event(new Registered($user));

        // Send email verification
        $user->sendEmailVerificationNotification();

        // Don't auto-login the user - they need to verify email first
        return redirect()->route('verification.notice')
            ->with('status', "Registration successful! Your Employee ID is: {$user->employee_id}. Please check your email to verify your account.");
    }
}
