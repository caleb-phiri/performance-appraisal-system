<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appraisal_kpas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appraisal_id')->constrained()->onDelete('cascade');
            $table->string('category');
            $table->string('kpa');
            $table->text('result_indicators');
            $table->integer('kpi');
            $table->integer('weight');
            $table->integer('self_rating')->nullable();
            $table->integer('supervisor_rating')->nullable();
            $table->text('comments')->nullable();
            $table->text('supervisor_comments')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index('appraisal_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appraisal_kpas');
    }
};