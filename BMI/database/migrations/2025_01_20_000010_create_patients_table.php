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
            $table->unsignedBigInteger('facility_id')->nullable();
            $table->string('patient_number', 50)->unique();
            
            // Personal Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('nickname', 100)->nullable();
            $table->enum('sex', ['male', 'female']);
            $table->date('date_of_birth');
            $table->integer('age_months')->nullable();
            $table->string('place_of_birth')->nullable();
            
            // Location
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->text('address')->nullable();
            $table->json('coordinates')->nullable();
            
            // Admission Info
            $table->date('date_of_admission');
            $table->enum('admission_status', ['admitted', 'discharged', 'pending', 'transferred'])->default('pending');
            $table->decimal('admission_weight', 5, 2)->nullable();
            $table->decimal('admission_height', 5, 2)->nullable();
            
            // Family Information
            $table->string('mother_name')->nullable();
            $table->integer('mother_age')->nullable();
            $table->string('mother_education', 100)->nullable();
            $table->string('father_name')->nullable();
            $table->integer('father_age')->nullable();
            $table->string('father_education', 100)->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship', 100)->nullable();
            $table->string('guardian_contact')->nullable();
            
            // Household Info
            $table->integer('total_household_members')->default(1);
            $table->integer('household_adults')->default(0);
            $table->integer('household_children')->default(1);
            $table->boolean('is_twin')->default(false);
            $table->boolean('is_4ps_beneficiary')->default(false);
            
            // Birth and Early Development
            $table->decimal('birth_weight', 5, 2)->nullable();
            $table->decimal('birth_length', 5, 2)->nullable();
            $table->integer('gestational_age_weeks')->nullable();
            $table->enum('delivery_type', ['normal', 'cesarean', 'assisted'])->nullable();
            $table->text('birth_complications')->nullable();
            
            // Current Health Status
            $table->decimal('current_weight', 5, 2)->nullable();
            $table->decimal('current_height', 5, 2)->nullable();
            $table->decimal('whz_score', 4, 2)->nullable();
            $table->decimal('waz_score', 4, 2)->nullable();
            $table->decimal('haz_score', 4, 2)->nullable();
            
            // Feeding and Medical History
            $table->boolean('is_breastfeeding')->default(false);
            $table->integer('breastfeeding_duration_months')->nullable();
            $table->text('immunization_status')->nullable();
            $table->text('allergies')->nullable();
            
            // Medical Conditions
            $table->boolean('has_tuberculosis')->default(false);
            $table->boolean('has_malaria')->default(false);
            $table->boolean('has_congenital_anomalies')->default(false);
            $table->text('congenital_anomalies_details')->nullable();
            $table->text('other_medical_problems')->nullable();
            $table->boolean('has_edema')->default(false);
            $table->text('medical_history')->nullable();
            
            // Contact and Administrative
            $table->string('contact_number')->nullable();
            $table->string('alternate_contact')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->enum('status', ['active', 'inactive', 'discharged', 'transferred', 'deceased'])->default('active');
            $table->string('photo', 500)->nullable();
            
            // Metadata
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('set null');
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('patients')->onDelete('set null');
            
            $table->index(['first_name', 'last_name', 'patient_number']);
            $table->index(['age_months', 'sex']);
            $table->index('facility_id');
            $table->index('status');
            $table->index(['admission_status', 'date_of_admission']);
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