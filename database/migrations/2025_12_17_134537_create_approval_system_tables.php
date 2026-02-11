<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First, add missing columns to users table
        Schema::table('users', function (Blueprint $table) {
            // Add missing columns from your previous design
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('email');
            }
            if (!Schema::hasColumn('users', 'last_activity')) {
                $table->timestamp('last_activity')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('users', 'attendance_score')) {
                $table->decimal('attendance_score', 5, 2)->nullable()->after('last_activity');
            }
            if (!Schema::hasColumn('users', 'task_completion_rate')) {
                $table->decimal('task_completion_rate', 5, 2)->nullable()->after('attendance_score');
            }
        });

        // Create approval_requests table with employee_number foreign keys
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            
            // Use employee_number instead of user_id
            $table->string('employee_number'); // Employee making the request
            $table->string('supervisor_number'); // Supervisor who needs to approve
            
            $table->string('type'); // leave, overtime, shift_change, profile_update, etc.
            $table->text('details');
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
            $table->text('rejection_reason')->nullable();
            $table->string('priority')->default('medium'); // high, medium, low
            
            // For leave requests
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('duration')->nullable(); // in days
            $table->string('leave_type')->nullable(); // annual, sick, emergency
            
            // For overtime requests
            $table->date('ot_date')->nullable();
            $table->decimal('ot_hours', 4, 2)->nullable();
            $table->text('ot_reason')->nullable();
            
            // Timestamps
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('employee_number')->references('employee_number')->on('users')->onDelete('cascade');
            $table->foreign('supervisor_number')->references('employee_number')->on('users')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['employee_number', 'status']);
            $table->index(['supervisor_number', 'status']);
            $table->index(['type', 'status']);
            $table->index('priority');
        });

        // Create employee_ratings table
        Schema::create('employee_ratings', function (Blueprint $table) {
            $table->id();
            
            // Use employee_number
            $table->string('employee_number'); // Employee being rated
            $table->string('supervisor_number'); // Supervisor giving the rating
            
            $table->integer('rating'); // 1-5
            $table->text('comments')->nullable();
            $table->string('category'); // performance, attendance, teamwork, initiative, quality
            
            // Additional details
            $table->date('rating_date')->default(now()->toDateString());
            $table->string('period')->nullable(); // daily, weekly, monthly, quarterly
            
            // For performance metrics
            $table->json('metrics')->nullable(); // Store additional metrics as JSON
            
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('employee_number')->references('employee_number')->on('users')->onDelete('cascade');
            $table->foreign('supervisor_number')->references('employee_number')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['employee_number', 'rating_date']);
            $table->index(['supervisor_number', 'employee_number']);
            $table->index('category');
            
            // Prevent duplicate ratings for same period
            $table->unique(['employee_number', 'supervisor_number', 'period', 'rating_date'], 'unique_period_rating');
        });

        // Create notification_logs table for tracking approvals
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number'); // Recipient
            $table->string('type'); // approval_request, approval_status, rating_given, etc.
            $table->text('message');
            $table->json('data')->nullable(); // Store additional data as JSON
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->foreign('employee_number')->references('employee_number')->on('users')->onDelete('cascade');
            $table->index(['employee_number', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('employee_ratings');
        Schema::dropIfExists('approval_requests');
        
        // Remove columns if they exist (optional)
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'last_activity', 'attendance_score', 'task_completion_rate']);
        });
    }
};