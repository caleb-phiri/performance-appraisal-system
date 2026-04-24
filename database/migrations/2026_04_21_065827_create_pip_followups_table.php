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
            $table->foreignId('appraisal_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('comment');
            $table->string('author_name')->nullable();
            $table->string('author_type')->default('supervisor'); // supervisor, hr, admin
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pip_followups');
    }
};