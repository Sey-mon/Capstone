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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('municipality');
            $table->string('barangay');
            $table->integer('age_months');
            $table->enum('sex', ['male', 'female']);
            $table->date('date_of_admission');
            $table->enum('admission_status', ['admitted', 'discharged', 'pending']);
            
            // Household information
            $table->integer('total_household_members');
            $table->integer('household_adults');
            $table->integer('household_children');
            $table->boolean('is_twin')->default(false);
            $table->boolean('is_4ps_beneficiary')->default(false);
            
            // Nutritional measurements
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->decimal('height', 5, 2)->nullable(); // in cm
            $table->decimal('whz_score', 4, 2)->nullable(); // Weight-for-Height Z-score
            $table->boolean('is_breastfeeding')->default(false);
            
            // Medical problems
            $table->boolean('has_tuberculosis')->default(false);
            $table->boolean('has_malaria')->default(false);
            $table->boolean('has_congenital_anomalies')->default(false);
            $table->text('other_medical_problems')->nullable();
            $table->boolean('has_edema')->default(false);
            
            // Additional fields for compatibility
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->enum('status', ['active', 'inactive', 'discharged'])->default('active');
            $table->text('medical_history')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
