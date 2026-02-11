<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('employee_number')->primary();
            $table->string('name');
            $table->string('job_title');
            $table->string('user_type'); // employee or supervisor
            $table->string('workstation_type'); // hq or toll_plaza
            $table->string('toll_plaza')->nullable();
            $table->string('hq_department')->nullable();
            $table->string('department');
            $table->string('manager_id')->nullable(); // References employee_number
            
            // Add these for Laravel authentication
            $table->string('password')->nullable(); // Can be null if using employee number only
            $table->string('email')->nullable(); // Can be null
            
            $table->integer('num_employees')->default(0);
            $table->rememberToken(); // For "remember me" functionality
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};