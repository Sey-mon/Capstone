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
        Schema::create('barangays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('municipality_id')->nullable();
            $table->string('name');
            $table->string('code', 20);
            $table->integer('population')->default(0);
            $table->string('health_facility')->nullable();
            $table->json('coordinates')->nullable();
            $table->timestamps();
            
            $table->foreign('municipality_id')->references('id')->on('municipalities')->onDelete('set null');
            $table->index('municipality_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangays');
    }
}; 