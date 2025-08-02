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
        Schema::create('treatment_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nutrition_assessment_id')->constrained('nutrition_assessments')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            
            // Recommendation Details
            $table->string('treatment_protocol', 100)->nullable(); // WHO Standard, Custom, etc.
            $table->string('intervention_type', 100); // RUTF, RUSF, Counseling, etc.
            $table->string('product_name')->nullable(); // Specific product name
            $table->string('dosage')->nullable();
            $table->string('frequency', 100)->nullable();
            $table->integer('duration_weeks')->nullable();
            
            // Therapeutic Feeding
            $table->decimal('daily_kcal', 6, 2)->nullable();
            $table->decimal('daily_protein_g', 6, 2)->nullable();
            $table->string('feeding_schedule')->nullable();
            
            // Status
            $table->enum('recommendation_status', ['pending', 'approved', 'started', 'completed', 'discontinued'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('discontinuation_reason')->nullable();
            
            // Follow-up
            $table->date('next_review_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->integer('expected_weight_gain_g_per_week')->nullable();
            
            // Notes and Counseling
            $table->text('clinical_notes')->nullable();
            $table->text('parent_counseling')->nullable();
            $table->text('dietary_advice')->nullable();
            $table->text('danger_signs_education')->nullable();
            
            // Monitoring
            $table->boolean('requires_inpatient_care')->default(false);
            $table->json('monitoring_parameters')->nullable();
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
            
            // Indexes with shorter names
            $table->index(['nutrition_assessment_id', 'recommendation_status'], 'tr_assessment_status_idx');
            $table->index('patient_id', 'tr_patient_idx');
            $table->index('recommendation_status', 'tr_status_idx');
            $table->index('next_review_date', 'tr_review_date_idx');
            $table->index('priority', 'tr_priority_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_recommendations');
    }
};
