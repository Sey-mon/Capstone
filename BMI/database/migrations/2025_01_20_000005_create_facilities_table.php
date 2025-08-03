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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['hospital', 'health_center', 'clinic', 'nutrition_center']);
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->text('address')->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('license_number', 100)->nullable();
            $table->integer('capacity')->default(0);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->json('coordinates')->nullable();
            $table->timestamps();
            
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('set null');
            $table->index(['type', 'status']);
            $table->index('barangay_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
}; 