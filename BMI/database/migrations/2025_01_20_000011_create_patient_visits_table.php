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
        Schema::create('patient_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->date('visit_date');
            $table->time('visit_time')->nullable();
            $table->enum('visit_type', ['initial', 'follow_up', 'emergency', 'discharge', 'home_visit']);
            $table->unsignedBigInteger('conducted_by')->nullable();
            
            // Measurements
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('muac', 4, 1)->nullable(); // Mid-Upper Arm Circumference
            $table->decimal('head_circumference', 4, 1)->nullable();
            $table->decimal('temperature', 4, 2)->nullable();
            
            // Clinical Assessment
            $table->enum('general_condition', ['good', 'fair', 'poor', 'critical'])->nullable();
            $table->enum('appetite', ['poor', 'fair', 'good', 'excellent'])->nullable();
            $table->enum('activity_level', ['lethargic', 'reduced', 'normal', 'hyperactive'])->nullable();
            
            // Symptoms
            $table->boolean('has_fever')->default(false);
            $table->boolean('has_cough')->default(false);
            $table->boolean('has_vomiting')->default(false);
            $table->boolean('has_diarrhea')->default(false);
            $table->boolean('has_edema')->default(false);
            $table->text('other_symptoms')->nullable();
            
            // Visit Notes
            $table->text('clinical_notes')->nullable();
            $table->text('treatment_response')->nullable();
            $table->text('family_compliance')->nullable();
            $table->text('challenges')->nullable();
            
            // Follow-up
            $table->date('next_visit_date')->nullable();
            $table->string('next_visit_type', 100)->nullable();
            $table->integer('visit_duration_minutes')->nullable();
            
            $table->timestamps();
            
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('conducted_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['patient_id', 'visit_date']);
            $table->index('visit_type');
            $table->index('next_visit_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_visits');
    }
}; 
