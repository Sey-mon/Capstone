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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Action Details
            $table->string('action', 50); // create, update, delete, view, login, logout
            $table->string('table_name', 100)->nullable(); // Table that was affected
            $table->string('record_id')->nullable(); // ID of the affected record
            
            // Change Tracking
            $table->json('old_values')->nullable(); // Previous values
            $table->json('new_values')->nullable(); // New values
            $table->json('changed_fields')->nullable(); // List of changed field names
            
            // Request Context
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('http_method', 10)->nullable();
            $table->json('request_data')->nullable();
            
            // System Context
            $table->string('session_id')->nullable();
            $table->string('transaction_id')->nullable(); // For grouping related actions
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Additional Metadata
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Additional context
            $table->boolean('is_sensitive')->default(false); // Flag for sensitive operations
            
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes with shorter names
            $table->index(['user_id', 'created_at'], 'audit_user_date_idx');
            $table->index(['table_name', 'record_id'], 'audit_table_record_idx');
            $table->index(['action', 'created_at'], 'audit_action_date_idx');
            $table->index('severity', 'audit_severity_idx');
            $table->index('is_sensitive', 'audit_sensitive_idx');
            $table->index('created_at', 'audit_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
