<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        // For MySQL - Update to only have 3 roles: admin, nutritionist, parents
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'nutritionist', 'parents') NOT NULL DEFAULT 'parents'");
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        // For MySQL - Revert to original roles
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
    }
};
