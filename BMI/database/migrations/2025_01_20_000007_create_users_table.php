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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 50)->unique()->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_token')->nullable();
            $table->integer('email_verification_attempts')->default(0);
            $table->timestamp('last_verification_email_sent_at')->nullable();
            $table->timestamp('email_verification_expires_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            
            // Role and Access
            $table->enum('role', ['admin', 'nutritionist', 'parent_guardian'])->default('parent_guardian');
            $table->enum('status', ['pending', 'approved', 'rejected', 'email_pending', 'suspended'])->default('email_pending');
            
            // Professional Info
            $table->unsignedBigInteger('facility_id')->nullable();
            $table->string('position')->nullable();
            $table->string('license_number', 50)->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->string('department', 100)->nullable();
            
            // Contact Info
            $table->string('phone_number', 20)->nullable();
            $table->string('alternate_phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->unsignedBigInteger('barangay_id')->nullable();
            
            // Profile
            $table->string('profile_photo', 500)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('sex', ['male', 'female'])->nullable();
            
            // Security
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            
            $table->timestamps();
            
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('set null');
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('set null');
            
            $table->index(['role', 'status']);
            $table->index('facility_id');
            $table->index('is_active');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}; 