<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pip_action_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appraisal_id');
            $table->string('action');
            $table->text('objective')->nullable();
            $table->text('measurement')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->foreign('appraisal_id')
                  ->references('id')
                  ->on('appraisals')
                  ->onDelete('cascade');
                  
            $table->index('appraisal_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pip_action_plans');
    }
};