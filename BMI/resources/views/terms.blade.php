<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - LakasApp</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        .terms-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .terms-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #2196f3;
        }
        .terms-content {
            line-height: 1.6;
            color: #333;
        }
        .terms-content h2 {
            color: #2196f3;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .terms-content h3 {
            color: #555;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .terms-content p {
            margin-bottom: 1rem;
        }
        .terms-content ul {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }
        .terms-content li {
            margin-bottom: 0.5rem;
        }
        .back-link {
            display: inline-block;
            margin-top: 2rem;
            color: #2196f3;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="terms-container">
        <div class="terms-header">
            <h1>Terms and Conditions</h1>
            <p>LakasApp: Smart Nutrition for Kids</p>
        </div>

        <div class="terms-content">
            <p><strong>Last updated:</strong> {{ date('F d, Y') }}</p>

            <h2>1. Acceptance of Terms</h2>
            <p>By accessing and using LakasApp ("the Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>

            <h2>2. Description of Service</h2>
            <p>LakasApp is a nutrition management system designed to help parents, guardians, and healthcare professionals track and manage children's nutritional health. The service includes:</p>
            <ul>
                <li>Patient registration and profile management</li>
                <li>Nutritional assessment and tracking</li>
                <li>Treatment recommendations and protocols</li>
                <li>Inventory management for healthcare facilities</li>
                <li>Communication tools between healthcare providers and families</li>
            </ul>

            <h2>3. User Accounts</h2>
            <h3>3.1 Account Creation</h3>
            <p>To use certain features of the Service, you must create an account. You agree to provide accurate, current, and complete information during registration and to update such information to keep it accurate, current, and complete.</p>

            <h3>3.2 Account Security</h3>
            <p>You are responsible for safeguarding the password and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use of your account.</p>

            <h3>3.3 Account Types</h3>
            <p>The Service supports different types of accounts:</p>
            <ul>
                <li><strong>Parent/Guardian:</strong> For families to manage their children's nutritional health</li>
                <li><strong>Nutritionist:</strong> For healthcare professionals to provide nutritional services</li>
                <li><strong>Administrator:</strong> For system management and oversight</li>
            </ul>

            <h2>4. Privacy and Data Protection</h2>
            <p>Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the Service, to understand our practices regarding the collection and use of your personal information.</p>

            <h2>5. Acceptable Use</h2>
            <p>You agree not to use the Service to:</p>
            <ul>
                <li>Violate any applicable laws or regulations</li>
                <li>Infringe upon the rights of others</li>
                <li>Transmit harmful, offensive, or inappropriate content</li>
                <li>Attempt to gain unauthorized access to the Service</li>
                <li>Interfere with or disrupt the Service</li>
            </ul>

            <h2>6. Medical Disclaimer</h2>
            <p>The information provided through LakasApp is for educational and informational purposes only. It is not intended as a substitute for professional medical advice, diagnosis, or treatment. Always seek the advice of qualified healthcare providers with questions you may have regarding medical conditions.</p>

            <h2>7. Intellectual Property</h2>
            <p>The Service and its original content, features, and functionality are and will remain the exclusive property of LakasApp and its licensors. The Service is protected by copyright, trademark, and other laws.</p>

            <h2>8. Termination</h2>
            <p>We may terminate or suspend your account and bar access to the Service immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever and without limitation, including but not limited to a breach of the Terms.</p>

            <h2>9. Limitation of Liability</h2>
            <p>In no event shall LakasApp, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your use of the Service.</p>

            <h2>10. Changes to Terms</h2>
            <p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days notice prior to any new terms taking effect.</p>

            <h2>11. Contact Information</h2>
            <p>If you have any questions about these Terms and Conditions, please contact us at:</p>
            <ul>
                <li>Email: support@lakasapp.com</li>
                <li>Phone: +63 XXX XXX XXXX</li>
                <li>Address: [Your Address Here]</li>
            </ul>

            <a href="javascript:history.back()" class="back-link">&larr; Back to Previous Page</a>
        </div>
    </div>
</body>
</html> 