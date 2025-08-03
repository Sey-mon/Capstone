<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LakasApp: Smart Nutrition for Kids</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="auth-container register-container">
        <div class="auth-header">
            <h1>Register for LakasApp</h1>
            <p>Create your account and provide patient information</p>
        </div>

        <div class="role-info">
            ℹ️ New accounts are created as parent/guardian users by default. Admin access is managed separately.
        </div>

        <div class="register-progress">
            <span id="register-progress"></span>
        </div>
        <a href="{{ route('login') }}" class="login-back-link">&larr; Back to Login</a>

        <form method="POST" action="{{ route('register') }}" id="register-form">
            @csrf
            <div class="register-step"> <!-- Step 1: Account Information & Basic Info -->
                <div class="form-section">
                    <h3>Account Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input 
                                id="first_name" 
                                class="form-input" 
                                type="text" 
                                name="first_name" 
                                value="{{ old('first_name') }}" 
                                required 
                                autofocus 
                                autocomplete="given-name"
                                placeholder="Enter your first name"
                            />
                            @error('first_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="middle_name">Middle Name (Optional)</label>
                            <input 
                                id="middle_name" 
                                class="form-input" 
                                type="text" 
                                name="middle_name" 
                                value="{{ old('middle_name') }}" 
                                autocomplete="additional-name"
                                placeholder="Enter your middle name"
                            />
                            @error('middle_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input 
                                id="last_name" 
                                class="form-input" 
                                type="text" 
                                name="last_name" 
                                value="{{ old('last_name') }}" 
                                required 
                                autocomplete="family-name"
                                placeholder="Enter your last name"
                            />
                            @error('last_name')
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
                                pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                            />
                            <small class="form-help-text">A verification link will be sent to your email. Format: user@domain.com</small>
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
                                pattern="[A-Za-z0-9@#$%^&*!]{8,}"
                                minlength="8"
                            />
                            <small class="form-help-text">
                                Password must be at least 8 characters long<br>
                                ✓ Allowed: Letters (a-z, A-Z), Numbers (0-9), Special chars (@#$%^&*!)<br>
                                ✓ Example: MyPass123! or SecureKey#9
                            </small>
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
                            <label for="phone_number">Phone Number (Optional)</label>
                            <input 
                                id="phone_number" 
                                class="form-input" 
                                type="tel" 
                                name="phone_number" 
                                value="{{ old('phone_number') }}" 
                                placeholder="Enter your phone number"
                                pattern="[0-9+\-\s()]{7,20}"
                            />
                            @error('phone_number')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="barangay_id">Barangay</label>
                            <select id="barangay_id" class="form-input" name="barangay_id" required>
                                <option value="">Select barangay</option>
                                @foreach($barangays as $barangay)
                                    <option value="{{ $barangay->id }}" {{ old('barangay_id') == $barangay->id ? 'selected' : '' }}>
                                        {{ $barangay->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('barangay_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="address">Address (Optional)</label>
                            <textarea 
                                id="address" 
                                class="form-input" 
                                name="address" 
                                placeholder="Enter your complete address"
                                rows="3"
                            >{{ old('address') }}</textarea>
                            @error('address')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input 
                                id="date_of_birth" 
                                class="form-input" 
                                type="date" 
                                name="date_of_birth" 
                                value="{{ old('date_of_birth') }}" 
                                required
                                max="{{ date('Y-m-d') }}"
                            />
                            @error('date_of_birth')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <fieldset>
                                <legend class="form-label">Sex</legend>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="radio" id="sex_male" name="sex" value="male" {{ old('sex') == 'male' ? 'checked' : '' }} required>
                                        <label for="sex_male">Male</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="radio" id="sex_female" name="sex" value="female" {{ old('sex') == 'female' ? 'checked' : '' }} required>
                                        <label for="sex_female">Female</label>
                                    </div>
                                </div>
                            </fieldset>
                            @error('sex')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <button class="auth-btn register-next" type="button">Next</button>
            </div>
            <div class="register-step register-step-hidden"> <!-- Step 2: Household Information -->
                <div class="form-section">
                    <h3>Household Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="total_household_members">Total Household Members</label>
                            <select 
                                id="total_household_members" 
                                class="form-input" 
                                name="total_household_members" 
                                required
                            >
                                <option value="">Select total members</option>
                                @for ($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}" {{ old('total_household_members') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('total_household_members')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="household_adults">Number of Adults</label>
                            <select 
                                id="household_adults" 
                                class="form-input" 
                                name="household_adults" 
                                required
                            >
                                <option value="">Select number of adults</option>
                                @for ($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}" {{ old('household_adults') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('household_adults')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="household_children">Number of Children</label>
                            <select 
                                id="household_children" 
                                class="form-input" 
                                name="household_children" 
                                required
                            >
                                <option value="">Select number of children</option>
                                @for ($i = 0; $i <= 20; $i++)
                                    <option value="{{ $i }}" {{ old('household_children') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
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
                    <div class="form-row">
                        <div class="form-group">
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="terms_accepted" name="terms_accepted" value="1" {{ old('terms_accepted') ? 'checked' : '' }} required>
                                    <label for="terms_accepted">
                                        I agree to the 
                                        <a href="/terms" target="_blank" style="color: #2196f3; text-decoration: underline;">Terms & Conditions</a>
                                        and 
                                        <a href="/privacy" target="_blank" style="color: #2196f3; text-decoration: underline;">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>
                            @error('terms_accepted')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <button class="auth-btn register-prev" type="button">Back</button>
                <button type="submit" class="auth-btn register-btn">Create Account</button>
            </div>
        </form>

        <div class="auth-links">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}">Sign in here</a>
        </div>
    </div>
    <script src="{{ asset('js/auth.js') }}"></script>
    <script src="{{ asset('js/register.js') }}"></script>
</body>
</html>
