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
        Schema::create('nutrition_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('visit_id')->nullable();
            $table->unsignedBigInteger('assessed_by')->nullable();
            $table->date('assessment_date');
            $table->time('assessment_time')->nullable();
            $table->enum('assessment_location', ['clinic', 'home', 'community', 'outreach'])->default('clinic');
            
            // Anthropometric Measurements
            $table->decimal('weight', 5, 2);
            $table->decimal('height', 5, 2);
            
            // Z-Scores and Indices
            $table->decimal('bmi', 4, 2);
            $table->decimal('whz_score', 5, 2)->nullable();
            $table->decimal('waz_score', 5, 2)->nullable();
            $table->decimal('haz_score', 5, 2)->nullable();
            $table->decimal('bmiz_score', 5, 2)->nullable();
            
            // Clinical Signs
            $table->boolean('edema')->default(false);
            $table->enum('edema_grade', ['none', 'mild', 'moderate', 'severe'])->default('none');
            $table->text('skin_changes')->nullable();
            $table->text('hair_changes')->nullable();
            $table->text('eye_changes')->nullable();
            $table->text('mouth_changes')->nullable();
            
            // Classification
            $table->enum('nutrition_status', ['normal', 'mild_malnutrition', 'moderate_malnutrition', 'severe_malnutrition']);
            $table->enum('malnutrition_type', ['none', 'acute', 'chronic', 'acute_on_chronic'])->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical']);
            
            // API Integration
            $table->decimal('confidence_score', 5, 4)->nullable();
            $table->longText('api_response')->nullable();
            $table->string('model_version', 20)->nullable();
            $table->enum('assessment_method', ['manual', 'api', 'hybrid'])->default('manual');
            
            // Clinical Assessment
            $table->text('symptoms')->nullable();
            $table->text('dietary_intake')->nullable();
            $table->text('feeding_practices')->nullable();
            $table->enum('appetite', ['poor', 'fair', 'good', 'excellent'])->nullable();
            $table->integer('meal_frequency')->nullable();
            $table->integer('food_diversity_score')->nullable();
            
            // Associated Conditions
            $table->boolean('has_fever')->default(false);
            $table->boolean('has_cough')->default(false);
            $table->boolean('has_vomiting')->default(false);
            $table->boolean('has_diarrhea')->default(false);
            $table->boolean('has_skin_lesions')->default(false);
            $table->text('clinical_signs')->nullable();
            
            // Assessment Results
            $table->text('recommendations')->nullable();
            $table->text('immediate_actions')->nullable();
            $table->boolean('referral_needed')->default(false);
            $table->string('referral_type', 100)->nullable();
            $table->boolean('hospitalization_needed')->default(false);
            
            // Follow-up
            $table->date('next_assessment_date')->nullable();
            $table->boolean('follow_up_required')->default(false);
            $table->integer('follow_up_interval_weeks')->nullable();
            
            // Environmental Factors
            $table->string('weather_conditions', 100)->nullable();
            $table->text('measurement_conditions')->nullable();
            $table->text('equipment_used')->nullable();
            
            // Notes and Documentation
            $table->text('notes')->nullable();
            $table->json('photos')->nullable(); // Array of photo paths
            
            $table->timestamps();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('visit_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('assessed_by')->references('id')->on('patients')->onDelete('set null');
            
            $table->index(['patient_id', 'assessment_date']);
            $table->index(['assessment_date', 'nutrition_status']);
            $table->index(['risk_level', 'follow_up_required']);
            $table->index('assessment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrition_assessments');
    }
}; 

