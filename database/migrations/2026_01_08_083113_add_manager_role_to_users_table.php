// database/migrations/2024_01_01_000000_add_manager_role_fields_to_users_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add manager-specific fields
            $table->string('manager_role')->nullable()->after('user_type');
            $table->boolean('can_approve_appraisals')->default(false)->after('manager_role');
            $table->boolean('can_view_all_appraisals')->default(false)->after('can_approve_appraisals');
            $table->integer('max_subordinates')->nullable()->after('can_view_all_appraisals');
            $table->json('appraisal_permissions')->nullable()->after('max_subordinates');
            
            // Add fields for multiple supervisor system
            $table->boolean('has_multiple_supervisors')->default(false)->after('appraisal_permissions');
            $table->string('primary_supervisor_id')->nullable()->after('has_multiple_supervisors');
            
            // Add index for performance
            $table->index(['user_type', 'manager_role']);
            $table->index('primary_supervisor_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'manager_role',
                'can_approve_appraisals',
                'can_view_all_appraisals',
                'max_subordinates',
                'appraisal_permissions',
                'has_multiple_supervisors',
                'primary_supervisor_id'
            ]);
            
            $table->dropIndex(['user_type', 'manager_role']);
            $table->dropIndex(['primary_supervisor_id']);
        });
    }
};