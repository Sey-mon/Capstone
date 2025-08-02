<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\InventoryItem;

class MalnutritionSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users with the 3 roles
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'facility_name' => 'Main Health Center',
                'license_number' => 'ADM001',
                'phone_number' => '+1234567890',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'nutritionist@example.com'],
            [
                'name' => 'Dr. Maria Santos',
                'email' => 'nutritionist@example.com',
                'password' => Hash::make('password'),
                'role' => 'nutritionist',
                'facility_name' => 'Nutrition Center',
                'license_number' => 'NUT001',
                'phone_number' => '+1234567891',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'parent@example.com'],
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'parent@example.com',
                'password' => Hash::make('password'),
                'role' => 'parents',
                'phone_number' => '+1234567892',
                'is_active' => true,
            ]
        );
    }
}
