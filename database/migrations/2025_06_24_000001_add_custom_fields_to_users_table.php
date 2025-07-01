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
            $table->string('username')->unique()->after('name');
            $table->string('full_name')->nullable()->after('username');
            $table->string('role')->default('user')->after('full_name');
            $table->string('status')->default('active')->after('role');
            $table->integer('login_attempts')->default(0)->after('status');
            $table->timestamp('last_login')->nullable()->after('login_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'full_name', 'role', 'status', 'login_attempts', 'last_login']);
        });
    }
};
