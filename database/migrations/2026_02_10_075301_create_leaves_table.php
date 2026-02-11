<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number');
            $table->string('employee_name');
            $table->string('job_title');
            $table->string('department');
            $table->enum('leave_type', ['annual', 'sick', 'maternity', 'paternity', 'unpaid', 'other']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days');
            $table->text('reason');
            $table->text('contact_address')->nullable();
            $table->string('contact_phone')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('remarks')->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('employee_number')->references('employee_number')->on('users')->onDelete('cascade');
            $table->index(['employee_number', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('leaves');
    }
};