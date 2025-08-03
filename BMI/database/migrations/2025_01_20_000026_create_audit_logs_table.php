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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('batch_id')->nullable();
            
            // Action Information
            $table->string('action', 50);
            $table->string('table_name', 100)->nullable();
            $table->string('record_id')->nullable();
            
            // Change Tracking
            $table->longText('old_values')->nullable();
            $table->longText('new_values')->nullable();
            $table->longText('changed_fields')->nullable();
            
            // Request Information
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('http_method', 10)->nullable();
            $table->longText('request_data')->nullable();
            $table->string('session_id', 255)->nullable();
            
            // Transaction and Risk
            $table->string('transaction_id', 255)->nullable();
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->integer('risk_score')->default(0);
            
            // Context
            $table->text('description')->nullable();
            $table->longText('metadata')->nullable();
            $table->json('geolocation')->nullable();
            
            // Classification
            $table->boolean('is_sensitive')->default(false);
            $table->boolean('is_automated')->default(false);
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['user_id', 'action']);
            $table->index(['table_name', 'record_id']);
            $table->index('severity');
            $table->index('created_at');
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
