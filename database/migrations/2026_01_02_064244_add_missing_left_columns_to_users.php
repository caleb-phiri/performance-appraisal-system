<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // For SQLite, we need to add columns one by one
        if (!Schema::hasColumn('users', 'left_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('left_at')->nullable()->after('left_company');
            });
        }
        
        if (!Schema::hasColumn('users', 'left_reason')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('left_reason', 50)->nullable()->after('left_at');
            });
        }
        
        if (!Schema::hasColumn('users', 'left_notes')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('left_notes')->nullable()->after('left_reason');
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['left_at', 'left_reason', 'left_notes'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};