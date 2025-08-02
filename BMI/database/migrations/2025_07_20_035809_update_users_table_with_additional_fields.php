<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Essential professional information
            $table->string('facility_name')->nullable()->after('role');
            $table->string('license_number', 50)->nullable()->after('facility_name');
            $table->string('phone_number', 20)->nullable()->after('license_number');
            $table->string('barangay');
            
            // Security and status
            $table->boolean('is_active')->default(true)->after('phone_number');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            
            // Indexes
            $table->index('role');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'facility_name',
                'license_number',
                'phone_number',
                'is_active',
                'last_login_at'
            ]);
            
            $table->dropIndex(['role']);
            $table->dropIndex(['is_active']);
        });
    }
};
