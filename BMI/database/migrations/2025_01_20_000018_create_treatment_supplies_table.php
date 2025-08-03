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
        Schema::create('treatment_supplies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_recommendation_id')->nullable();
            $table->unsignedBigInteger('inventory_item_id')->nullable();
            
            // Prescription Details
            $table->decimal('quantity_prescribed', 8, 2);
            $table->text('dosage_instructions')->nullable();
            $table->string('frequency', 100)->nullable();
            $table->integer('duration_days')->nullable();
            
            // Dispensing Information
            $table->decimal('quantity_dispensed', 8, 2)->default(0);
            $table->decimal('quantity_remaining', 8, 2)->virtualAs('quantity_prescribed - quantity_dispensed');
            $table->date('dispensed_date')->nullable();
            $table->unsignedBigInteger('dispensed_by')->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->date('expiry_date')->nullable();
            
            // Status
            $table->enum('status', ['prescribed', 'partially_dispensed', 'fully_dispensed', 'cancelled'])->default('prescribed');
            
            // Notes
            $table->text('notes')->nullable();
            $table->text('side_effects_observed')->nullable();
            $table->enum('patient_tolerance', ['good', 'fair', 'poor'])->nullable();
            
            $table->timestamps();
            $table->foreign('treatment_recommendation_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('inventory_item_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('dispensed_by')->references('id')->on('patients')->onDelete('set null');
            
            $table->index('treatment_recommendation_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_supplies');
    }
}; 

