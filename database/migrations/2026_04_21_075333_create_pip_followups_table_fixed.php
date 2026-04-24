<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pip_followups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appraisal_id');
            $table->string('employee_number'); // This matches your user's primary key
            $table->text('comment');
            $table->string('author_name')->nullable();
            $table->string('author_type')->default('supervisor');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('appraisal_id');
            $table->index('employee_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pip_followups');
    }
};