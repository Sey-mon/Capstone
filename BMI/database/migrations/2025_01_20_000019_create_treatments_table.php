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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('nutrition_assessment_id')->nullable();
            $table->unsignedBigInteger('treatment_recommendation_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            
            // Treatment Information
            $table->string('treatment_type', 100);
            $table->string('protocol', 100)->nullable();
            $table->enum('phase', ['phase1', 'phase2', 'phase3', 'maintenance'])->default('phase1');
            
            // Medication/Supply Details
            $table->string('dosage')->nullable();
            $table->string('frequency', 100)->nullable();
            $table->integer('duration_weeks')->nullable();
            
            // Status and Timeline
            $table->enum('status', ['pending', 'active', 'completed', 'discontinued', 'on_hold'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('actual_end_date')->nullable();
            
            // Progress Tracking
            $table->date('next_review_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            
            // Clinical Documentation
            $table->longText('progress_notes')->nullable();
            $table->text('clinical_notes')->nullable();
            $table->text('parent_counseling')->nullable();
            $table->text('compliance_notes')->nullable();
            $table->text('side_effects')->nullable();
            $table->text('complications')->nullable();
            
            // Outcomes
            $table->enum('treatment_response', ['excellent', 'good', 'fair', 'poor', 'no_response'])->nullable();
            $table->integer('weight_gain_achieved_g')->nullable();
            $table->boolean('target_achieved')->default(false);
            $table->text('discontinuation_reason')->nullable();
            
            $table->timestamps();
            
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('nutrition_assessment_id')->references('id')->on('nutrition_assessments')->onDelete('set null');
            $table->foreign('treatment_recommendation_id')->references('id')->on('treatment_recommendations')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index('patient_id');
            $table->index('status');
            $table->index('priority');
            $table->index('next_review_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
}; 
