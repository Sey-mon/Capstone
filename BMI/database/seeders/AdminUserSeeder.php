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
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'status' => 'approved',
        ]);

        // Create regular user
        User::firstOrCreate([
            'email' => 'user@example.com',
        ], [
            'first_name' => 'Regular',
            'last_name' => 'User',
            'password' => Hash::make('password'),
            'role' => 'parent_guardian',
            'email_verified_at' => now(),
            'status' => 'approved',
        ]);
        
        // Create nutritionist user
        User::firstOrCreate([
            'email' => 'nutritionist@example.com',
        ], [
            'first_name' => 'Nutritionist',
            'last_name' => 'User',
            'password' => Hash::make('password'),
            'role' => 'nutritionist',
            'email_verified_at' => now(),
            'status' => 'approved',
        ]);
    }
}
