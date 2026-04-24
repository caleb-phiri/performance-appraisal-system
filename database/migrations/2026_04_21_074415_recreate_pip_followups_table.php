<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop existing table if it exists
        Schema::dropIfExists('pip_followups');
        
        // Create new table without foreign key constraints for SQLite compatibility
        Schema::create('pip_followups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appraisal_id');
            $table->unsignedBigInteger('user_id');
            $table->text('comment');
            $table->string('author_name')->nullable();
            $table->string('author_type')->default('supervisor');
            $table->timestamps();
            
            // Add indexes for performance
            $table->index('appraisal_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pip_followups');
    }
};