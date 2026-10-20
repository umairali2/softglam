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
            $table->boolean('is_staff')->default(false)->after('remember_token');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('status_reason')->nullable()->after('verification_deadline_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_staff');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status_reason');
        });
    }
};
