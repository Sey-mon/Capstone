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
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'license_number' => 'ADM001',
                'phone_number' => '+1234567890',
                'is_active' => true,
                'status' => 'approved',
            ]
        );

        User::firstOrCreate(
            ['email' => 'nutritionist@example.com'],
            [
                'first_name' => 'Dr. Maria',
                'last_name' => 'Santos',
                'email' => 'nutritionist@example.com',
                'password' => Hash::make('password'),
                'role' => 'nutritionist',
                'license_number' => 'NUT001',
                'phone_number' => '+1234567891',
                'is_active' => true,
                'status' => 'approved',
            ]
        );

        User::firstOrCreate(
            ['email' => 'parent@example.com'],
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'email' => 'parent@example.com',
                'password' => Hash::make('password'),
                'role' => 'parent_guardian',
                'phone_number' => '+1234567892',
                'is_active' => true,
                'status' => 'approved',
            ]
        );
    }
}
