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
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('assessment_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            
            // Request Information
            $table->string('endpoint', 255);
            $table->string('http_method', 10)->default('POST');
            $table->string('api_version', 20)->nullable();
            
            // Request/Response Data
            $table->longText('request_data')->nullable();
            $table->text('request_headers')->nullable();
            $table->longText('response_data')->nullable();
            $table->integer('response_status');
            $table->integer('response_time_ms')->nullable();
            
            // Error Handling
            $table->text('error_message')->nullable();
            $table->text('error_stack_trace')->nullable();
            $table->boolean('is_successful')->default(true);
            $table->integer('retry_count')->default(0);
            
            // Client Information
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_id', 255)->nullable();
            
            // Performance Metrics
            $table->integer('memory_usage_mb')->nullable();
            $table->decimal('cpu_usage_percent', 5, 2)->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            $table->foreign('assessment_id')->references('id')->on('nutrition_assessments')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['endpoint', 'response_status']);
            $table->index('is_successful');
            $table->index('created_at');
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
