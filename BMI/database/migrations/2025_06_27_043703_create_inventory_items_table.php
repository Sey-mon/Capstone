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
            $table->string('name');
            $table->string('barangay');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->enum('category', [
                'therapeutic_food',
                'supplements',
                'medical_supplies',
                'equipment',
                'medications',
                'medicine', // allow both singular and plural
                'other'
            ]);
            $table->enum('unit', ['pieces', 'boxes', 'packets', 'bottles', 'kg', 'liters']);
            $table->integer('current_stock')->default(0);
            $table->integer('minimum_stock')->default(10);
            $table->integer('maximum_stock')->default(1000);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('supplier')->nullable();
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->timestamps();
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
