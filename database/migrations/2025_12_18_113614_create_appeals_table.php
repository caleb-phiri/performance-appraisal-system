<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number');
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->string('appraisal_period')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->string('resolved_by')->nullable();
            $table->timestamp('review_started_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->index(['employee_number', 'status']);
            
            // Add foreign key constraint
            $table->foreign('employee_number')
                  ->references('employee_number')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appeals');
    }
};