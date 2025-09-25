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
        Schema::table('products', function (Blueprint $table) {
            // Option 1: JSON storage (recommended for simple cases)
            $table->json('price_range')->nullable();
            
            // Option 2: Separate columns (recommended for complex queries)
            // $table->integer('min_price')->nullable();
            // $table->integer('max_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price_range');
            
            // If using separate columns, uncomment these:
            // $table->dropColumn(['min_price', 'max_price']);
        });
    }
};
