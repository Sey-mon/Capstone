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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_item_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('reference_type', ['patient_treatment', 'stock_adjustment', 'donation', 'purchase', 'transfer', 'wastage'])->nullable();
            $table->unsignedBigInteger('reference_id')->nullable(); // Can reference treatment_id, patient_id, etc.
            
            // Transaction Details
            $table->enum('type', ['stock_in', 'stock_out', 'adjustment', 'expired', 'damaged', 'transfer_in', 'transfer_out', 'return']);
            $table->integer('quantity');
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            
            // Stock Levels
            $table->integer('previous_stock');
            $table->integer('new_stock');
            
            // Transaction Info
            $table->date('transaction_date');
            $table->string('batch_number', 100)->nullable();
            $table->string('lot_number', 100)->nullable();
            $table->date('expiry_date')->nullable();
            
            // Documentation
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('receipt_number', 100)->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            
            $table->timestamps();
            $table->foreign('inventory_item_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('reference_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('patients')->onDelete('set null');
            
            $table->index(['inventory_item_id', 'transaction_date']);
            $table->index('type');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
}; 

