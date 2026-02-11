// database/migrations/2024_01_01_000005_create_appraisal_entries_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appraisal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appraisal_id')->constrained()->onDelete('cascade');
            $table->foreignId('kpa_template_id')->constrained('kpa_templates');
            $table->integer('self_rating')->nullable()->check('self_rating BETWEEN 1 AND 4');
            $table->integer('manager_rating')->nullable()->check('manager_rating BETWEEN 1 AND 4');
            $table->text('employee_comments')->nullable();
            $table->text('manager_comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appraisal_entries');
    }
};