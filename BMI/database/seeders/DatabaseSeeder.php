<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            \Database\Seeders\BarangaySeeder::class,
            \Database\Seeders\AdminUserSeeder::class,
            \Database\Seeders\PatientSeeder::class,
            \Database\Seeders\InventoryItemSeeder::class,
            \Database\Seeders\SystemSettingsSeeder::class,
            \Database\Seeders\EmailTemplatesSeeder::class,
        ]);
    }
}
