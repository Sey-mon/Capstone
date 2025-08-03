document.addEventListener('DOMContentLoaded', function() {
    const steps = Array.from(document.querySelectorAll('.register-step'));
    const progress = document.getElementById('register-progress');
    const nextBtns = document.querySelectorAll('.register-next');
    const prevBtns = document.querySelectorAll('.register-prev');
    let currentStep = 0;

    // Enhanced password validation
    function validatePassword(password) {
        const validationRules = {
            length: password.length >= 8,
            hasLetter: /[A-Za-z]/.test(password),
            hasNumber: /[0-9]/.test(password),
            validChars: /^[A-Za-z0-9@#$%^&*!]+$/.test(password)
        };
        
        return {
            isValid: Object.values(validationRules).every(rule => rule),
            rules: validationRules
        };
    }

    // Enhanced email validation
    function validateEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
    }

    // Add real-time validation feedback
    const passwordInput = document.querySelector('#password');
    const emailInput = document.querySelector('#email');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const validation = validatePassword(this.value);
            const feedback = this.parentNode.querySelector('.password-feedback') || 
                           document.createElement('div');
            feedback.className = 'password-feedback';
            feedback.style.fontSize = '12px';
            feedback.style.marginTop = '5px';
            
            let feedbackText = '';
            if (this.value) {
                feedbackText += validation.rules.length ? '✓ ' : '✗ ';
                feedbackText += 'At least 8 characters<br>';
                
                feedbackText += validation.rules.hasLetter ? '✓ ' : '✗ ';
                feedbackText += 'Contains letters (a-z, A-Z)<br>';
                
                feedbackText += validation.rules.hasNumber ? '✓ ' : '✗ ';
                feedbackText += 'Contains numbers (0-9)<br>';
                
                feedbackText += validation.rules.validChars ? '✓ ' : '✗ ';
                feedbackText += 'Only allowed characters (letters, numbers, @#$%^&*!)';
                
                feedback.style.color = validation.isValid ? '#16a085' : '#e74c3c';
            }
            
            feedback.innerHTML = feedbackText;
            if (!this.parentNode.querySelector('.password-feedback')) {
                this.parentNode.appendChild(feedback);
            }
        });
    }

    if (emailInput) {
        emailInput.addEventListener('input', function() {
            const isValid = validateEmail(this.value);
            const feedback = this.parentNode.querySelector('.email-feedback') || 
                           document.createElement('div');
            feedback.className = 'email-feedback';
            feedback.style.fontSize = '12px';
            feedback.style.marginTop = '5px';
            
            if (this.value) {
                feedback.innerHTML = isValid ? 
                    '<span style="color:#16a085;">✓ Valid email format</span>' : 
                    '<span style="color:#e74c3c;">✗ Please enter a valid email address</span>';
                
                if (!this.parentNode.querySelector('.email-feedback')) {
                    this.parentNode.appendChild(feedback);
                }
            } else {
                feedback.remove();
            }
        });
    }

    function showStep(idx) {
        steps.forEach((step, i) => {
            step.style.display = (i === idx) ? 'block' : 'none';
        });
        if (progress) {
            progress.textContent = `Step ${idx + 1} of ${steps.length}`;
        }
    }

    function validateStep(idx) {
        // Remove previous errors
        steps[idx].querySelectorAll('.error-message').forEach(e => e.remove());
        let valid = true;
        // Required fields per step (update as needed to match your backend/database)
        const requiredFields = [
            // Step 0: Account Information
            ['name', 'email', 'password', 'password_confirmation', 'municipality', 'barangay', 'date_of_birth', 'sex'],
            // Step 1: Household Information
            ['total_household_members', 'household_adults', 'household_children']
        ];
        
        if (idx < requiredFields.length) {
            requiredFields[idx].forEach(field => {
                const input = steps[idx].querySelector(`[name="${field}"]`);
                if (input && !input.value) {
                    valid = false;
                    const err = document.createElement('div');
                    err.className = 'error-message';
                    err.textContent = 'This field is required.';
                    input.parentNode.appendChild(err);
                }
            });
        }
        
        // Enhanced email validation for step 0
        if (idx === 0) {
            const emailInput = steps[0].querySelector('[name="email"]');
            if (emailInput && emailInput.value && !validateEmail(emailInput.value)) {
                valid = false;
                const err = document.createElement('div');
                err.className = 'error-message';
                err.textContent = 'Please enter a valid email address.';
                emailInput.parentNode.appendChild(err);
            }
            
            // Enhanced password validation
            const pw = steps[0].querySelector('[name="password"]');
            if (pw && pw.value) {
                const validation = validatePassword(pw.value);
                if (!validation.isValid) {
                    valid = false;
                    const err = document.createElement('div');
                    err.className = 'error-message';
                    err.textContent = 'Password must be at least 8 characters with letters, numbers, and only allowed special characters (@#$%^&*!).';
                    pw.parentNode.appendChild(err);
                }
            }
        }
        
        // Password confirmation match
        if (idx === 0) {
            const pw = steps[0].querySelector('[name="password"]');
            const pwc = steps[0].querySelector('[name="password_confirmation"]');
            if (pw && pwc && pw.value !== pwc.value) {
                valid = false;
                const err = document.createElement('div');
                err.className = 'error-message';
                err.textContent = 'Passwords do not match.';
                pwc.parentNode.appendChild(err);
            }
        }
        return valid;
    }

    nextBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (validateStep(currentStep)) {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });
    });
    prevBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });
    showStep(currentStep);
}); 