<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('appraisals', function (Blueprint $table) {
        $table->boolean('pip_employee_signed')->default(false);
        $table->boolean('pip_supervisor_signed')->default(false);
        $table->timestamp('pip_employee_signed_at')->nullable();
        $table->timestamp('pip_supervisor_signed_at')->nullable();
        $table->string('pip_employee_signature_path')->nullable();
        $table->string('pip_supervisor_signature_path')->nullable();
    });
}

public function down()
{
    Schema::table('appraisals', function (Blueprint $table) {
        $table->dropColumn([
            'pip_employee_signed',
            'pip_supervisor_signed',
            'pip_employee_signed_at',
            'pip_supervisor_signed_at',
            'pip_employee_signature_path',
            'pip_supervisor_signature_path'
        ]);
    });
}
};
