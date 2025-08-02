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
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('assessed_by')->constrained('users');
            $table->date('assessment_date');
            $table->decimal('weight', 5, 2); // kg
            $table->decimal('height', 5, 2); // cm
            $table->decimal('bmi', 4, 2);
            $table->enum('nutrition_status', [
                'normal', 
                'mild_malnutrition', 
                'moderate_malnutrition', 
                'severe_malnutrition'
            ]);
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical']);
            $table->text('symptoms')->nullable();
            $table->text('dietary_intake')->nullable();
            $table->text('clinical_signs')->nullable();
            $table->text('recommendations')->nullable();
            $table->date('next_assessment_date')->nullable();
            $table->timestamps();
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
