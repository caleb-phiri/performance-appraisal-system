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
            // Add the column
            $table->boolean('password_setup_skipped')->default(false)->after('password');
            
            // Also add is_onboarded column if it doesn't exist
            if (!Schema::hasColumn('users', 'is_onboarded')) {
                $table->boolean('is_onboarded')->default(false)->after('remember_token');
            }
            
            if (!Schema::hasColumn('users', 'onboarded_at')) {
                $table->timestamp('onboarded_at')->nullable()->after('is_onboarded');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_setup_skipped');
            $table->dropColumn(['is_onboarded', 'onboarded_at']);
        });
    }
};