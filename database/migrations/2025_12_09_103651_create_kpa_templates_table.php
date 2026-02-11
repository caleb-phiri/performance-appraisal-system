// database/migrations/2024_01_01_000003_create_kpa_templates_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpa_templates', function (Blueprint $table) {
            $table->id();
            $table->string('kpa');
            $table->text('kpi_description');
            $table->text('rating_standard');
            $table->decimal('weight', 5, 2);
            $table->string('category')->default('Core');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpa_templates');
    }
};