<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Patient;

// Test the database connection and patient creation
try {
    // Test creating a patient with the new structure
    $patient = Patient::create([
        'name' => 'Test Patient',
        'municipality' => 'Test Municipality',
        'barangay' => 'Test Barangay',
        'age_months' => 24,
        'sex' => 'male',
        'date_of_admission' => '2024-01-15',
        'admission_status' => 'admitted',
        'total_household_members' => 5,
        'household_adults' => 2,
        'household_children' => 3,
        'is_twin' => false,
        'is_4ps_beneficiary' => true,
        'weight' => 12.5,
        'height' => 85.0,
        'whz_score' => -1.2,
        'is_breastfeeding' => false,
        'has_tuberculosis' => false,
        'has_malaria' => false,
        'has_congenital_anomalies' => false,
        'other_medical_problems' => null,
        'has_edema' => false,
    ]);

    echo "✅ Patient created successfully!\n";
    echo "Patient ID: " . $patient->id . "\n";
    echo "Name: " . $patient->name . "\n";
    echo "Age: " . $patient->age_months . " months (" . $patient->age_years . " years)\n";
    echo "Location: " . $patient->municipality . ", " . $patient->barangay . "\n";
    echo "BMI: " . ($patient->bmi ?? 'N/A') . "\n";
    echo "Medical Problems: " . implode(', ', $patient->medical_problems_array) . "\n";

    // Test retrieving patients
    $totalPatients = Patient::count();
    echo "\n📊 Total patients in database: " . $totalPatients . "\n";

    // Test municipalities
    $municipalities = Patient::distinct()->pluck('municipality');
    echo "🏘️ Municipalities: " . $municipalities->implode(', ') . "\n";

    echo "\n✅ Database structure test completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
