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
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('nutrition_assessment_id')->nullable()->constrained('nutrition_assessments')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Treatment Details
            $table->string('treatment_type', 100); // RUTF, RUSF, Counseling, etc.
            $table->string('protocol', 100)->nullable(); // WHO Standard, Custom, etc.
            $table->string('dosage')->nullable();
            $table->string('frequency', 100)->nullable();
            $table->integer('duration_weeks')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'active', 'completed', 'discontinued'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('next_review_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            
            // Progress tracking
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->json('progress_notes')->nullable();
            
            // Notes
            $table->text('clinical_notes')->nullable();
            $table->text('parent_counseling')->nullable();
            $table->text('side_effects')->nullable();
            
            $table->timestamps();
            
            // Indexes 
            $table->index(['patient_id', 'status'], 'treatments_patient_status_idx');
            $table->index('next_review_date', 'treatments_review_date_idx');
            $table->index('priority', 'treatments_priority_idx');
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
