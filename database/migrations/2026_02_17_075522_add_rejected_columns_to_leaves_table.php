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
        Schema::table('leaves', function (Blueprint $table) {
            if (!Schema::hasColumn('leaves', 'rejected_by')) {
                $table->string('rejected_by')->nullable()->after('remarks');
            }
            if (!Schema::hasColumn('leaves', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }
            if (!Schema::hasColumn('leaves', 'approved_by')) {
                $table->string('approved_by')->nullable()->after('rejected_at');
            }
            if (!Schema::hasColumn('leaves', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $columns = ['rejected_by', 'rejected_at', 'approved_by', 'approved_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('leaves', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};