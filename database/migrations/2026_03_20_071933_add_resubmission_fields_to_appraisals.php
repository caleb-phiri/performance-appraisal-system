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
        Schema::table('appraisals', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('appraisals', 'resubmitted_at')) {
                $table->timestamp('resubmitted_at')->nullable()->after('submitted_at');
            }
            
            if (!Schema::hasColumn('appraisals', 'resubmitted_by')) {
                $table->string('resubmitted_by')->nullable()->after('resubmitted_at');
            }
            
            if (!Schema::hasColumn('appraisals', 'resubmission_count')) {
                $table->integer('resubmission_count')->default(0)->after('resubmitted_by');
            }
            
            if (!Schema::hasColumn('appraisals', 'agreement_option')) {
                $table->string('agreement_option')->nullable()->after('resubmission_count');
            }
            
            if (!Schema::hasColumn('appraisals', 'manager_reason')) {
                $table->text('manager_reason')->nullable()->after('agreement_option');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appraisals', function (Blueprint $table) {
            $table->dropColumn([
                'resubmitted_at',
                'resubmitted_by',
                'resubmission_count',
                'agreement_option',
                'manager_reason'
            ]);
        });
    }
};