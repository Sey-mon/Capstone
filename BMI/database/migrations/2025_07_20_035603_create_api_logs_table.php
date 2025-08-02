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
            $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('set null');
            $table->foreignId('assessment_id')->nullable()->constrained('nutrition_assessments')->onDelete('set null');
            
            // API Details
            $table->string('endpoint');
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->integer('response_status');
            $table->integer('response_time_ms')->nullable();
            
            // Request Information
            $table->string('http_method', 10)->default('POST');
            $table->string('api_version', 20)->nullable();
            $table->text('request_headers')->nullable();
            
            // Error Tracking
            $table->text('error_message')->nullable();
            $table->text('error_stack_trace')->nullable();
            $table->boolean('is_successful')->default(true);
            $table->integer('retry_count')->default(0);
            
            // User Context
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_id')->nullable();
            
            // Performance Metrics
            $table->integer('memory_usage_mb')->nullable();
            $table->decimal('cpu_usage_percent', 5, 2)->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes
            $table->index(['patient_id', 'created_at'], 'api_patient_date_idx');
            $table->index(['assessment_id', 'created_at'], 'api_assessment_date_idx');
            $table->index(['is_successful', 'created_at'], 'api_success_date_idx');
            $table->index('endpoint', 'api_endpoint_idx');
            $table->index('response_status', 'api_status_idx');
            $table->index('created_at', 'api_created_idx');
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
