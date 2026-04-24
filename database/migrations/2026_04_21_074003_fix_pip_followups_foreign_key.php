<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop existing table if needed (or modify)
        Schema::dropIfExists('pip_followups');
        
        Schema::create('pip_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appraisal_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // This references users.id
            $table->text('comment');
            $table->string('author_name')->nullable();
            $table->string('author_type')->default('supervisor');
            $table->timestamps();
            
            // Add index for better performance
            $table->index(['appraisal_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pip_followups');
    }
};