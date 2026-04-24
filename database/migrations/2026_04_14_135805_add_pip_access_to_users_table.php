<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pip_access_level')) {
                $table->string('pip_access_level')->default('none');
            }
            if (!Schema::hasColumn('users', 'can_view_pip')) {
                $table->boolean('can_view_pip')->default(false);
            }
            if (!Schema::hasColumn('users', 'can_manage_pip')) {
                $table->boolean('can_manage_pip')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pip_access_level', 'can_view_pip', 'can_manage_pip']);
        });
    }
};