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
            // Add supervisor notes column
            if (!Schema::hasColumn('users', 'supervisor_notes')) {
                $table->text('supervisor_notes')->nullable()->after('notes');
            }
            
            // Add promoted to supervisor at column
            if (!Schema::hasColumn('users', 'promoted_to_supervisor_at')) {
                $table->timestamp('promoted_to_supervisor_at')->nullable()->after('supervisor_notes');
            }
            
            // Add promoted by column (who promoted this user)
            if (!Schema::hasColumn('users', 'promoted_by')) {
                $table->unsignedBigInteger('promoted_by')->nullable()->after('promoted_to_supervisor_at');
            }
            
            // Add department column if it doesn't exist (optional)
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('employee_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'supervisor_notes',
                'promoted_to_supervisor_at',
                'promoted_by',
                'department'
            ]);
        });
    }
};