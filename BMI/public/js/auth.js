// BMI Laravel Project - Authentication JavaScript
// This file contains any interactive functionality for the auth pages

document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on first input field if it exists
    const firstInput = document.querySelector('.form-input');
    if (firstInput) {
        firstInput.focus();
    }

    // Add some interactive feedback for form submissions
    const authForms = document.querySelectorAll('form[method="POST"]');
    authForms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('.auth-btn');
            if (submitBtn) {
                submitBtn.textContent = 'Please wait...';
                submitBtn.style.opacity = '0.7';
                submitBtn.disabled = true;
            }
        });
    });

    // Add smooth transitions to form elements
    const formInputs = document.querySelectorAll('.form-input');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Function to copy test credentials (optional feature)
function copyCredentials(type) {
    const credentials = {
        admin: 'admin@example.com',
        user: 'user@example.com'
    };
    
    if (navigator.clipboard && credentials[type]) {
        navigator.clipboard.writeText(credentials[type]).then(function() {
            console.log('Credentials copied to clipboard');
        });
    }
}
