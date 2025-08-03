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
            $table->unsignedBigInteger('nutrition_assessment_id')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('protocol_id')->nullable();
            
            // Treatment Details
            $table->string('treatment_protocol', 100)->nullable();
            $table->string('intervention_type', 100);
            $table->string('product_name')->nullable();
            $table->string('dosage')->nullable();
            $table->string('frequency', 100)->nullable();
            $table->integer('duration_weeks')->nullable();
            
            // Nutritional Targets
            $table->decimal('daily_kcal', 6, 2)->nullable();
            $table->decimal('daily_protein_g', 6, 2)->nullable();
            $table->decimal('daily_fat_g', 6, 2)->nullable();
            $table->integer('target_weight_gain_g_per_week')->nullable();
            $table->string('feeding_schedule')->nullable();
            
            // Status and Dates
            $table->enum('recommendation_status', ['pending', 'approved', 'started', 'completed', 'discontinued', 'modified'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->text('discontinuation_reason')->nullable();
            
            // Follow-up
            $table->date('next_review_date')->nullable();
            $table->integer('review_frequency_weeks')->default(2);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Clinical Information
            $table->text('clinical_notes')->nullable();
            $table->text('parent_counseling')->nullable();
            $table->text('dietary_advice')->nullable();
            $table->text('danger_signs_education')->nullable();
            $table->boolean('requires_inpatient_care')->default(false);
            $table->json('monitoring_parameters')->nullable();
            
            // Success Metrics
            $table->decimal('target_weight', 5, 2)->nullable();
            $table->decimal('target_height', 5, 2)->nullable();
            $table->decimal('target_muac', 4, 1)->nullable();
            $table->text('success_criteria')->nullable();
            
            // Approval Workflow
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->timestamp('modified_at')->nullable();
            
            $table->timestamps();
            $table->foreign('nutrition_assessment_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('protocol_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('modified_by')->references('id')->on('patients')->onDelete('set null');
            
            $table->index('patient_id');
            $table->index('recommendation_status');
            $table->index('priority');
            $table->index('next_review_date');
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

