<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'barangay' => 'Barangay 1',
        ]);

        // Create regular user
        User::firstOrCreate([
            'email' => 'user@example.com',
        ], [
            'name' => 'Regular User',
            'password' => Hash::make('password'),
            'role' => 'parents',
            'email_verified_at' => now(),
            'barangay' => 'Barangay 2',
        ]);
        
        // Create nutritionist user
        User::firstOrCreate([
            'email' => 'nutritionist@example.com',
        ], [
            'name' => 'Nutritionist User',
            'password' => Hash::make('password'),
            'role' => 'nutritionist',
            'email_verified_at' => now(),
            'barangay' => 'Barangay 3',
        ]);
    }
}
