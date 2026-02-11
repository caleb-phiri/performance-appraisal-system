<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add left_company field if it doesn't exist
            if (!Schema::hasColumn('users', 'left_company')) {
                $table->boolean('left_company')->default(false)->after('password_setup_skipped');
            }
            
            // Add left_at field if it doesn't exist
            if (!Schema::hasColumn('users', 'left_at')) {
                $table->timestamp('left_at')->nullable()->after('left_company');
            }
            
            // Add left_reason field if it doesn't exist
            if (!Schema::hasColumn('users', 'left_reason')) {
                $table->string('left_reason', 50)->nullable()->after('left_at');
            }
            
            // Add left_notes field if it doesn't exist
            if (!Schema::hasColumn('users', 'left_notes')) {
                $table->text('left_notes')->nullable()->after('left_reason');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['left_company', 'left_at', 'left_reason', 'left_notes']);
        });
    }
};