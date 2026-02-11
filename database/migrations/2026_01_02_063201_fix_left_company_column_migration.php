<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if the column exists before trying to add it
        if (!Schema::hasColumn('users', 'left_company')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('left_company')->default(false);
            });
        }
    }

    public function down()
    {
        // Only drop if it exists
        if (Schema::hasColumn('users', 'left_company')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('left_company');
            });
        }
    }
};