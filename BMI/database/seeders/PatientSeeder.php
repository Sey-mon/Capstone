<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Barangay;
use Faker\Factory as Faker;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $barangays = Barangay::all();

        foreach ($barangays as $barangay) {
            // Create 2 patients for each barangay
            for ($i = 1; $i <= 2; $i++) {
                $ageMonths = $faker->numberBetween(6, 60); // 6 months to 5 years
                $sex = $faker->randomElement(['male', 'female']);
                $firstName = $faker->firstName($sex === 'male' ? 'male' : 'female');
                $lastName = $faker->lastName();
                
                Patient::create([
                    'facility_id' => null,
                    'patient_number' => 'PAT-' . str_pad($barangay->id, 3, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'first_name' => $firstName,
                    'middle_name' => $faker->optional(0.3)->firstName(),
                    'last_name' => $lastName,
                    'nickname' => $faker->optional(0.4)->firstName(),
                    'sex' => $sex,
                    'date_of_birth' => now()->subMonths($ageMonths),
                    'age_months' => $ageMonths,
                    'place_of_birth' => $faker->city(),
                    'barangay_id' => $barangay->id,
                    'address' => $faker->streetAddress() . ', ' . $barangay->name,
                    'coordinates' => null,
                    'date_of_admission' => $faker->dateTimeBetween('-6 months', 'now'),
                    'admission_status' => $faker->randomElement(['admitted', 'discharged', 'pending']),
                    'admission_weight' => $faker->randomFloat(2, 5.0, 25.0),
                    'admission_height' => $faker->randomFloat(2, 50.0, 120.0),
                    'mother_name' => $faker->name('female'),
                    'mother_age' => $faker->numberBetween(20, 45),
                    'mother_education' => $faker->randomElement(['Elementary', 'High School', 'College', 'Post Graduate']),
                    'father_name' => $faker->name('male'),
                    'father_age' => $faker->numberBetween(22, 50),
                    'father_education' => $faker->randomElement(['Elementary', 'High School', 'College', 'Post Graduate']),
                    'guardian_name' => $faker->name(),
                    'guardian_relationship' => $faker->randomElement(['Mother', 'Father', 'Grandmother', 'Grandfather', 'Aunt', 'Uncle']),
                    'guardian_contact' => $faker->phoneNumber(),
                    'total_household_members' => $faker->numberBetween(3, 8),
                    'household_adults' => $faker->numberBetween(1, 4),
                    'household_children' => $faker->numberBetween(1, 5),
                    'is_twin' => $faker->boolean(10), // 10% chance of being a twin
                    'is_4ps_beneficiary' => $faker->boolean(30), // 30% chance of being 4Ps beneficiary
                    'birth_weight' => $faker->randomFloat(2, 2.0, 4.5),
                    'birth_length' => $faker->randomFloat(2, 40.0, 55.0),
                    'gestational_age_weeks' => $faker->numberBetween(28, 42),
                    'delivery_type' => $faker->randomElement(['normal', 'cesarean', 'assisted']),
                    'birth_complications' => $faker->optional(0.2)->sentence(),
                    'current_weight' => $faker->randomFloat(2, 5.0, 25.0),
                    'current_height' => $faker->randomFloat(2, 50.0, 120.0),
                    'whz_score' => $faker->randomFloat(2, -3.0, 2.0),
                    'waz_score' => $faker->randomFloat(2, -3.0, 2.0),
                    'haz_score' => $faker->randomFloat(2, -3.0, 2.0),
                    'is_breastfeeding' => $faker->boolean(40), // 40% chance of breastfeeding
                    'breastfeeding_duration_months' => $faker->optional(0.6)->numberBetween(1, 24),
                    'immunization_status' => $faker->optional(0.8)->sentence(),
                    'allergies' => $faker->optional(0.15)->sentence(),
                    'has_tuberculosis' => $faker->boolean(5), // 5% chance
                    'has_malaria' => $faker->boolean(3), // 3% chance
                    'has_congenital_anomalies' => $faker->boolean(2), // 2% chance
                    'congenital_anomalies_details' => $faker->optional(0.2)->sentence(),
                    'other_medical_problems' => $faker->optional(0.25)->sentence(),
                    'has_edema' => $faker->boolean(8), // 8% chance
                    'medical_history' => $faker->optional(0.3)->paragraph(),
                    'contact_number' => $faker->phoneNumber(),
                    'alternate_contact' => $faker->optional(0.7)->phoneNumber(),
                    'emergency_contact' => $faker->phoneNumber(),
                    'status' => $faker->randomElement(['active', 'inactive', 'discharged']),
                    'photo' => null,
                    'created_by' => null,
                    'parent_id' => null,
                ]);
            }
        }

        $this->command->info('Successfully seeded ' . ($barangays->count() * 2) . ' patients (2 per barangay)!');
    }
} 