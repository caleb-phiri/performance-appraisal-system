<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    protected $fillable = [
        'employee_number',
        'supervisor_number',
        'type',
        'details',
        'status',
        'rejection_reason',
        'priority',
        'start_date',
        'end_date',
        'duration',
        'leave_type',
        'ot_date',
        'ot_hours',
        'ot_reason',
        'approved_at',
        'rejected_at',
        'cancelled_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'ot_date' => 'date',
        'ot_hours' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    // Type constants
    const TYPE_LEAVE = 'leave';
    const TYPE_OVERTIME = 'overtime';
    const TYPE_SHIFT_CHANGE = 'shift_change';
    const TYPE_PROFILE_UPDATE = 'profile_update';
    const TYPE_EQUIPMENT = 'equipment_request';

    // Priority constants
    const PRIORITY_HIGH = 'high';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_LOW = 'low';

    // Leave type constants
    const LEAVE_ANNUAL = 'annual';
    const LEAVE_SICK = 'sick';
    const LEAVE_EMERGENCY = 'emergency';
    const LEAVE_MATERNITY = 'maternity';
    const LEAVE_PATERNITY = 'paternity';

    // Relationships
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_number', 'employee_number');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_number', 'employee_number');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeForSupervisor($query, $supervisorNumber)
    {
        return $query->where('supervisor_number', $supervisorNumber);
    }

    public function scopeForEmployee($query, $employeeNumber)
    {
        return $query->where('employee_number', $employeeNumber);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', self::PRIORITY_HIGH);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    // Methods
    public function approve($comments = null)
    {
        $this->status = self::STATUS_APPROVED;
        $this->approved_at = now();
        $this->rejection_reason = $comments;
        $this->save();

        // Log notification
        $this->logNotification('approved', $comments);
        
        return $this;
    }

    public function reject($reason)
    {
        $this->status = self::STATUS_REJECTED;
        $this->rejected_at = now();
        $this->rejection_reason = $reason;
        $this->save();

        // Log notification
        $this->logNotification('rejected', $reason);
        
        return $this;
    }

    public function cancel($reason = null)
    {
        $this->status = self::STATUS_CANCELLED;
        $this->cancelled_at = now();
        $this->rejection_reason = $reason;
        $this->save();
        
        return $this;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getDurationInDays()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date) + 1;
        }
        return $this->duration ?? 0;
    }

    public function getStatusBadge()
    {
        $badges = [
            self::STATUS_PENDING => '<span class="badge bg-warning">Pending</span>',
            self::STATUS_APPROVED => '<span class="badge bg-success">Approved</span>',
            self::STATUS_REJECTED => '<span class="badge bg-danger">Rejected</span>',
            self::STATUS_CANCELLED => '<span class="badge bg-secondary">Cancelled</span>',
        ];
        
        return $badges[$this->status] ?? $badges[self::STATUS_PENDING];
    }

    public function getPriorityBadge()
    {
        $badges = [
            self::PRIORITY_HIGH => '<span class="badge bg-danger">High</span>',
            self::PRIORITY_MEDIUM => '<span class="badge bg-warning">Medium</span>',
            self::PRIORITY_LOW => '<span class="badge bg-info">Low</span>',
        ];
        
        return $badges[$this->priority] ?? $badges[self::PRIORITY_MEDIUM];
    }

    private function logNotification($action, $comments = null)
    {
        $messages = [
            'approved' => 'Your request has been approved',
            'rejected' => 'Your request has been rejected',
        ];

        NotificationLog::create([
            'employee_number' => $this->employee_number,
            'type' => 'approval_' . $action,
            'message' => $messages[$action] . ($comments ? ": {$comments}" : ''),
            'data' => [
                'request_id' => $this->id,
                'type' => $this->type,
                'action' => $action,
                'comments' => $comments,
                'processed_by' => auth()->user()->employee_number,
            ],
        ]);
    }
}