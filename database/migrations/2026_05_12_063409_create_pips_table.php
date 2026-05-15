// database/migrations/xxxx_xx_xx_create_pips_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pips', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number');
            $table->string('initiated_by')->nullable();
            $table->string('initiated_by_name')->nullable();
            $table->date('pip_end_date')->nullable();
            $table->timestamps();
            
            // Add foreign key if users table exists
            $table->foreign('employee_number')->references('employee_number')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pips');
    }
};