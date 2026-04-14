<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appraisals', function (Blueprint $table) {
            $table->boolean('pip_initiated')->default(false)->after('status');
            $table->date('pip_start_date')->nullable()->after('pip_initiated');
            $table->date('pip_end_date')->nullable()->after('pip_start_date');
            $table->text('pip_plan')->nullable()->after('pip_end_date');
            $table->text('pip_supervisor_notes')->nullable()->after('pip_plan');
            $table->timestamp('pip_initiated_at')->nullable()->after('pip_supervisor_notes');
            $table->unsignedBigInteger('pip_initiated_by')->nullable()->after('pip_initiated_at');
        });
    }

    public function down()
    {
        Schema::table('appraisals', function (Blueprint $table) {
            $table->dropColumn([
                'pip_initiated',
                'pip_start_date',
                'pip_end_date',
                'pip_plan',
                'pip_supervisor_notes',
                'pip_initiated_at',
                'pip_initiated_by'
            ]);
        });
    }
};