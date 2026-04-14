<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appraisals', function (Blueprint $table) {
            if (!Schema::hasColumn('appraisals', 'pip_status')) {
                $table->string('pip_status')->default('active')->after('pip_initiated_by');
            }
        });
    }

    public function down()
    {
        Schema::table('appraisals', function (Blueprint $table) {
            if (Schema::hasColumn('appraisals', 'pip_status')) {
                $table->dropColumn('pip_status');
            }
        });
    }
};