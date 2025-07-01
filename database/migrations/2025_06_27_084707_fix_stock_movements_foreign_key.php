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
        Schema::table('stock_movements', function (Blueprint $table) {
            // Drop the existing foreign key constraint if it exists
            $table->dropForeign(['item_id']);
            
            // Recreate the foreign key constraint with correct reference
            $table->foreign('item_id')->references('item_id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Drop the corrected foreign key constraint
            $table->dropForeign(['item_id']);
            
            // Restore the original foreign key constraint
            $table->foreign('item_id')->references('id')->on('items');
        });
    }
};
