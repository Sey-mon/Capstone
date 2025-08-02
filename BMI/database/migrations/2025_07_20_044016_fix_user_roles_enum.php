<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First update any 'user' roles to 'parents'
        DB::statement("UPDATE users SET role = 'nutritionist' WHERE role = 'user'");
        
        // Then modify the enum
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'nutritionist', 'parents') NOT NULL DEFAULT 'parents'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'user', 'nutritionist') NOT NULL DEFAULT 'user'");
    }
};
