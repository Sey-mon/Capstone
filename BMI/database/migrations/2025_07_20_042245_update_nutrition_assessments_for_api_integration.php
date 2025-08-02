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
        Schema::table('nutrition_assessments', function (Blueprint $table) {
            // API Integration Fields
            $table->decimal('whz_score', 5, 2)->nullable()->after('bmi'); // Weight-for-Height Z-score
            $table->decimal('waz_score', 5, 2)->nullable()->after('whz_score'); // Weight-for-Age Z-score  
            $table->decimal('haz_score', 5, 2)->nullable()->after('waz_score'); // Height-for-Age Z-score
            $table->decimal('confidence_score', 5, 4)->nullable()->after('risk_level');
            $table->json('api_response')->nullable()->after('confidence_score'); // Full API response
            $table->string('model_version', 20)->nullable()->after('api_response');
            $table->enum('assessment_method', ['manual', 'api', 'hybrid'])->default('manual')->after('model_version');
            
            // Additional Clinical Fields
            $table->boolean('edema')->default(false)->after('height');
            $table->text('feeding_practices')->nullable()->after('dietary_intake');
            $table->enum('appetite', ['poor', 'fair', 'good'])->nullable()->after('feeding_practices');
            $table->boolean('vomiting')->default(false)->after('appetite');
            $table->boolean('diarrhea')->default(false)->after('vomiting');
            
            // Follow-up tracking
            $table->boolean('follow_up_required')->default(false)->after('next_assessment_date');
            $table->text('notes')->nullable()->after('recommendations');
            
            // Indexes for API integration 
            $table->index('assessment_method', 'na_method_idx');
            $table->index('follow_up_required', 'na_followup_idx');
        });
    }

    public function down(): void
    {
        Schema::table('nutrition_assessments', function (Blueprint $table) {
            $table->dropColumn([
                'whz_score',
                'waz_score', 
                'haz_score',
                'confidence_score',
                'api_response',
                'model_version',
                'assessment_method',
                'edema',
                'feeding_practices',
                'appetite',
                'vomiting',
                'diarrhea',
                'follow_up_required',
                'notes'
            ]);
            
            $table->dropIndex(['na_method_idx']);
            $table->dropIndex(['na_followup_idx']);
        });
    }
};
