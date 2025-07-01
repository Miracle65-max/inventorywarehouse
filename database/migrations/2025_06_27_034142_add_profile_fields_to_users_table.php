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
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('last_login');
            $table->string('phone', 20)->nullable()->after('bio');
            $table->string('address', 255)->nullable()->after('phone');
            $table->string('department', 100)->nullable()->after('address');
            $table->string('position', 100)->nullable()->after('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'phone', 'address', 'department', 'position']);
        });
    }
};
