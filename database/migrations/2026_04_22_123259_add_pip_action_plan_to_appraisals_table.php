<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appraisals', function (Blueprint $table) {
            // Add pip_action_plan column between pip_plan and pip_supervisor_notes
            if (!Schema::hasColumn('appraisals', 'pip_action_plan')) {
                $table->json('pip_action_plan')->nullable()->after('pip_plan');
            }
        });
    }

    public function down()
    {
        Schema::table('appraisals', function (Blueprint $table) {
            if (Schema::hasColumn('appraisals', 'pip_action_plan')) {
                $table->dropColumn('pip_action_plan');
            }
        });
    }
};