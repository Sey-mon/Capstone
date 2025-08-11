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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint');
            $table->string('method', 10);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('request_data')->nullable();
            $table->longText('response_data')->nullable();
            $table->string('status', 50)->default('success');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->decimal('execution_time', 8, 3)->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['endpoint', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
