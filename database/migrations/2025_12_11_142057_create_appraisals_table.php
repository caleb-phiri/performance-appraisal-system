<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appraisals', function (Blueprint $table) {
    $table->id();
    
    // Replace user_id with employee_number
    $table->string('employee_number'); // Employee number instead of user_id

    $table->string('period'); // Q1, Q2, Q3, Q4
    $table->date('start_date');
    $table->date('end_date');
    $table->enum('status', ['draft', 'submitted', 'in_review', 'completed', 'approved', 'archived'])->default('draft');
    $table->text('development_needs')->nullable();
    $table->text('employee_comments')->nullable();
    $table->text('supervisor_comments')->nullable();
    $table->integer('total_weight')->default(100);
    $table->decimal('self_score', 5, 2)->nullable();
    $table->decimal('supervisor_score', 5, 2)->nullable();
    $table->decimal('overall_score', 5, 2)->nullable();
    $table->string('rating')->nullable(); // Excellent, Good, etc.
    $table->string('approved_by')->nullable(); // Can also store employee_number of approver
    $table->timestamp('approved_at')->nullable();
    $table->timestamps();

    $table->index(['employee_number', 'period']);
    $table->index('status');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('appraisals');
    }
};