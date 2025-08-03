<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class NutritionistApplicationController extends Controller
{
    public function showForm()
    {
        return view('auth.apply-nutritionist');
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/'],
            'barangay' => 'nullable|string|max:255',
            'id_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'certificate' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $idPath = $request->file('id_document')->store('nutritionist_ids', 'public');
        $certPath = $request->file('certificate')->store('nutritionist_certificates', 'public');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'barangay' => $request->barangay,
            'role' => 'nutritionist',
            'status' => 'pending',
            'id_document' => $idPath,
            'certificate' => $certPath,
        ]);

        // Optionally: send notification to admin

        return redirect()->route('login')->with('status', 'Your application has been submitted and is pending admin approval.');
    }
} 