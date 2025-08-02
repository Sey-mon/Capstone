<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration - BMI System</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background-color: #f9fafb;
        }
        .form-section h3 {
            margin: 0 0 15px 0;
            color: #374151;
            font-size: 18px;
            font-weight: 600;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .checkbox-item input[type="checkbox"] {
            width: auto;
            margin: 0;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container register-container">
        <div class="auth-header">
            <h1>Patient Registration</h1>
            <p>Create your account and provide patient information</p>
        </div>

        <div class="role-info">
            ℹ️ New accounts are created as regular users by default. Admin access is managed separately.
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Account Information -->
            <div class="form-section">
                <h3>Account Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input 
                            id="name" 
                            class="form-input" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                            placeholder="Enter patient's full name"
                        />
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input 
                            id="email" 
                            class="form-input" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            placeholder="Enter your email"
                        />
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            id="password" 
                            class="form-input"
                            type="password"
                            name="password"
                            required 
                            autocomplete="new-password"
                            placeholder="Choose a strong password"
                        />
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input 
                            id="password_confirmation" 
                            class="form-input"
                            type="password"
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="Confirm your password"
                        />
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="role">Account Type</label>
                        <select id="role" class="form-input" name="role" required>
                            <option value="user">Parent/Guardian</option>
                            <option value="nutritionist">Nutritionist</option>
                        </select>
                        <div class="text-sm text-gray-500 mt-1">Admin accounts can only be created by existing administrators.</div>
                        @error('role')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="form-section">
                <h3>Basic Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="municipality">Municipality</label>
                        <input 
                            id="municipality" 
                            class="form-input" 
                            type="text" 
                            name="municipality" 
                            value="{{ old('municipality') }}" 
                            required
                            placeholder="Enter municipality"
                        />
                        @error('municipality')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="barangay">Barangay</label>
                        <input 
                            id="barangay" 
                            class="form-input" 
                            type="text" 
                            name="barangay" 
                            value="{{ old('barangay') }}" 
                            required
                            placeholder="Enter barangay"
                        />
                        @error('barangay')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="age_months">Age (months)</label>
                        <input 
                            id="age_months" 
                            class="form-input" 
                            type="number" 
                            name="age_months" 
                            value="{{ old('age_months') }}" 
                            required
                            min="0"
                            max="1200"
                            placeholder="Enter age in months"
                        />
                        @error('age_months')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sex">Sex</label>
                        <select id="sex" class="form-input" name="sex" required>
                            <option value="">Select sex</option>
                            <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('sex')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_admission">Date of Admission</label>
                        <input 
                            id="date_of_admission" 
                            class="form-input" 
                            type="date" 
                            name="date_of_admission" 
                            value="{{ old('date_of_admission') }}" 
                            required
                        />
                        @error('date_of_admission')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="admission_status">Admission Status</label>
                        <select id="admission_status" class="form-input" name="admission_status" required>
                            <option value="">Select status</option>
                            <option value="admitted" {{ old('admission_status') == 'admitted' ? 'selected' : '' }}>Admitted</option>
                            <option value="discharged" {{ old('admission_status') == 'discharged' ? 'selected' : '' }}>Discharged</option>
                            <option value="pending" {{ old('admission_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('admission_status')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Household Information -->
            <div class="form-section">
                <h3>Household Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="total_household_members">Total Household Members</label>
                        <input 
                            id="total_household_members" 
                            class="form-input" 
                            type="number" 
                            name="total_household_members" 
                            value="{{ old('total_household_members') }}" 
                            required
                            min="1"
                            placeholder="Total members"
                        />
                        @error('total_household_members')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="household_adults">Adults</label>
                        <input 
                            id="household_adults" 
                            class="form-input" 
                            type="number" 
                            name="household_adults" 
                            value="{{ old('household_adults') }}" 
                            required
                            min="0"
                            placeholder="Number of adults"
                        />
                        @error('household_adults')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="household_children">Children</label>
                        <input 
                            id="household_children" 
                            class="form-input" 
                            type="number" 
                            name="household_children" 
                            value="{{ old('household_children') }}" 
                            required
                            min="0"
                            placeholder="Number of children"
                        />
                        @error('household_children')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Special Conditions</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="is_twin" name="is_twin" value="1" {{ old('is_twin') ? 'checked' : '' }}>
                                <label for="is_twin">Twin</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="is_4ps_beneficiary" name="is_4ps_beneficiary" value="1" {{ old('is_4ps_beneficiary') ? 'checked' : '' }}>
                                <label for="is_4ps_beneficiary">4P's Beneficiary</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nutritional Measurements -->
            <div class="form-section">
                <h3>Nutritional Measurements</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="weight">Weight (kg)</label>
                        <input 
                            id="weight" 
                            class="form-input" 
                            type="number" 
                            name="weight" 
                            value="{{ old('weight') }}" 
                            step="0.01"
                            min="0"
                            max="500"
                            placeholder="Weight in kg"
                        />
                        @error('weight')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="height">Height (cm)</label>
                        <input 
                            id="height" 
                            class="form-input" 
                            type="number" 
                            name="height" 
                            value="{{ old('height') }}" 
                            step="0.01"
                            min="0"
                            max="300"
                            placeholder="Height in cm"
                        />
                        @error('height')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="whz_score">WHZ Score</label>
                        <input 
                            id="whz_score" 
                            class="form-input" 
                            type="number" 
                            name="whz_score" 
                            value="{{ old('whz_score') }}" 
                            step="0.01"
                            min="-5"
                            max="5"
                            placeholder="Weight-for-Height Z-score"
                        />
                        @error('whz_score')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Breastfeeding Status</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="is_breastfeeding" name="is_breastfeeding" value="1" {{ old('is_breastfeeding') ? 'checked' : '' }}>
                                <label for="is_breastfeeding">Currently Breastfeeding</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Problems -->
            <div class="form-section">
                <h3>Medical Problems</h3>
                <div class="form-group full-width">
                    <label>Medical Conditions</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="has_tuberculosis" name="has_tuberculosis" value="1" {{ old('has_tuberculosis') ? 'checked' : '' }}>
                            <label for="has_tuberculosis">Tuberculosis</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="has_malaria" name="has_malaria" value="1" {{ old('has_malaria') ? 'checked' : '' }}>
                            <label for="has_malaria">Malaria</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="has_congenital_anomalies" name="has_congenital_anomalies" value="1" {{ old('has_congenital_anomalies') ? 'checked' : '' }}>
                            <label for="has_congenital_anomalies">Congenital Anomalies</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="has_edema" name="has_edema" value="1" {{ old('has_edema') ? 'checked' : '' }}>
                            <label for="has_edema">Edema</label>
                        </div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="other_medical_problems">Other Medical Problems</label>
                    <textarea 
                        id="other_medical_problems" 
                        class="form-input" 
                        name="other_medical_problems" 
                        rows="3"
                        placeholder="Describe any other medical problems..."
                    >{{ old('other_medical_problems') }}</textarea>
                    @error('other_medical_problems')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="auth-btn register-btn">
                Create Patient Account
            </button>
        </form>

        <div class="auth-links">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}">Sign in here</a>
            
            <div style="margin-top: 15px;">
                <a href="{{ url('/home') }}">← Back to Home</a>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
