<?php
// app/Models/Leave.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leaves';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_number',
        'employee_name',
        'job_title',
        'department',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'contact_address',
        'contact_phone',
        'status',
        'remarks',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'total_days' => 'integer',
    ];

    /**
     * Get the employee that owns the leave.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_number', 'employee_number');
    }

    /**
     * Get the final approver for this leave.
     */
    public function finalApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'final_approver_number', 'employee_number');
    }

    /**
     * Get the approver who approved this leave.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'employee_number');
    }

    /**
     * Get the approver who rejected this leave.
     */
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by', 'employee_number');
    }

    /**
     * Get all approval requests for this leave.
     */
    public function approvalRequests(): MorphMany
    {
        return $this->morphMany(ApprovalRequest::class, 'requestable');
    }

    /**
     * Check if the leave is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the leave is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the leave is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if the leave is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_number', 'employee_number');
    }

    /**
     * Get the current approver for this leave.
     */
    public function getCurrentApproverAttribute()
    {
        if (!$this->isPending()) {
            return null;
        }

        $currentRequest = $this->approvalRequests()
            ->where('status', 'pending')
            ->orderBy('approval_level', 'asc')
            ->first();

        if ($currentRequest) {
            return (object) [
                'name' => $currentRequest->approver_name,
                'employee_number' => $currentRequest->approver_number,
                'role' => $currentRequest->approver_role,
                'level' => $currentRequest->approval_level
            ];
        }

        return null;
    }

    /**
     * Get the approval progress.
     */
    public function getApprovalProgressAttribute()
    {
        $totalLevels = $this->approvalRequests()->count();
        $completedLevels = $this->approvalRequests()
            ->whereIn('status', ['approved', 'rejected', 'cancelled'])
            ->count();
        
        return [
            'total' => $totalLevels,
            'completed' => $completedLevels,
            'percentage' => $totalLevels > 0 ? round(($completedLevels / $totalLevels) * 100) : 0
        ];
    }

    /**
     * Scope a query to only include pending leaves.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved leaves.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected leaves.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include leaves for a specific employee.
     */
    public function scopeForEmployee($query, $employeeNumber)
    {
        return $query->where('employee_number', $employeeNumber);
    }

    /**
     * Scope a query to only include leaves for a specific department.
     */
    public function scopeForDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope a query to only include leaves within a date range.
     */
    public function scopeWithinDateRange($query, $startDate, $endDate)
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function($query) use ($startDate, $endDate) {
                  $query->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
              });
        });
    }

    /**
     * Get the leave type in readable format.
     */
    public function getLeaveTypeNameAttribute(): string
    {
        $types = [
            'annual' => 'Annual Leave',
            'sick' => 'Sick Leave',
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'unpaid' => 'Unpaid Leave',
            'study' => 'Study Leave',
            'compassionate' => 'Compassionate Leave',
            'other' => 'Other Leave',
        ];

        return $types[$this->leave_type] ?? ucfirst($this->leave_type);
    }

    /**
     * Get the status in readable format.
     */
    public function getStatusNameAttribute(): string
    {
        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get the status color for badges.
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'cancelled' => 'gray',
        ];

        return $colors[$this->status] ?? 'gray';
    }
}