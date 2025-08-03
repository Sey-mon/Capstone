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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facility_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->text('description')->nullable();
            
            // Classification
            $table->enum('category', ['therapeutic_food', 'supplements', 'medical_supplies', 'equipment', 'medications', 'medicine', 'other']);
            $table->boolean('is_nutrition_supply')->default(false);
            $table->enum('nutrition_type', ['RUTF', 'RUSF', 'F75', 'F100', 'Supplements', 'Other'])->nullable();
            
            // Nutritional Information
            $table->decimal('kcal_per_serving', 6, 2)->nullable();
            $table->decimal('protein_per_serving_g', 6, 2)->nullable();
            $table->decimal('fat_per_serving_g', 6, 2)->nullable();
            $table->decimal('carbs_per_serving_g', 6, 2)->nullable();
            $table->string('serving_size', 100)->nullable();
            $table->string('target_age_group')->nullable();
            $table->boolean('is_therapeutic')->default(false);
            
            // Inventory Management
            $table->enum('unit', ['pieces', 'boxes', 'packets', 'bottles', 'kg', 'grams', 'liters', 'ml', 'doses', 'vials']);
            $table->integer('current_stock')->default(0);
            $table->integer('reserved_stock')->default(0);
            $table->integer('available_stock')->virtualAs('current_stock - reserved_stock');
            $table->integer('minimum_stock')->default(10);
            $table->integer('maximum_stock')->default(1000);
            $table->integer('reorder_point')->default(20);
            
            // Costing
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('average_cost', 10, 2)->nullable();
            $table->decimal('last_purchase_cost', 10, 2)->nullable();
            
            // Product Details
            $table->string('batch_number', 100)->nullable();
            $table->string('lot_number', 100)->nullable();
            $table->date('manufacture_date')->nullable();
            $table->date('expiry_date')->nullable();
            
            // Storage Requirements
            $table->decimal('storage_temperature_min', 4, 1)->nullable();
            $table->decimal('storage_temperature_max', 4, 1)->nullable();
            $table->text('storage_conditions')->nullable();
            
            // Source and Quality
            $table->string('donation_source')->nullable();
            $table->enum('quality_grade', ['A', 'B', 'C'])->nullable();
            $table->text('certification')->nullable();
            
            // Status
            $table->enum('status', ['active', 'inactive', 'discontinued', 'expired', 'recalled'])->default('active');
            
            $table->timestamps();
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('inventory_categories')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            
            $table->index(['category', 'status']);
            $table->index('expiry_date');
            $table->index(['current_stock', 'minimum_stock']);
            $table->index('facility_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
}; 

