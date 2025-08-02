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
        Schema::table('inventory_items', function (Blueprint $table) {
            // Add nutrition-specific fields
            $table->boolean('is_nutrition_supply')->default(false)->after('category');
            $table->enum('nutrition_type', ['RUTF', 'RUSF', 'F75', 'F100', 'Supplements', 'Other'])->nullable()->after('is_nutrition_supply');
            $table->decimal('kcal_per_serving', 6, 2)->nullable()->after('nutrition_type');
            $table->decimal('protein_per_serving_g', 6, 2)->nullable()->after('kcal_per_serving');
            $table->string('target_age_group')->nullable()->after('protein_per_serving_g');
            $table->boolean('is_therapeutic')->default(false)->after('target_age_group');
            
            // Add indexes with shorter names
            $table->index('is_nutrition_supply', 'inv_nutrition_idx');
            $table->index('nutrition_type', 'inv_type_idx');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn([
                'is_nutrition_supply',
                'nutrition_type',
                'kcal_per_serving',
                'protein_per_serving_g',
                'target_age_group',
                'is_therapeutic'
            ]);
            
            $table->dropIndex(['inv_nutrition_idx']);
            $table->dropIndex(['inv_type_idx']);
        });
    }
};
