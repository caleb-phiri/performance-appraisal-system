<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'employee_number',
        'type',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Type constants
    const TYPE_APPROVAL_REQUEST = 'approval_request';
    const TYPE_APPROVAL_APPROVED = 'approval_approved';
    const TYPE_APPROVAL_REJECTED = 'approval_rejected';
    const TYPE_RATING_GIVEN = 'rating_given';
    const TYPE_RATING_RECEIVED = 'rating_received';
    const TYPE_SYSTEM_ALERT = 'system_alert';

    // Relationships
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_number', 'employee_number');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForEmployee($query, $employeeNumber)
    {
        return $query->where('employee_number', $employeeNumber);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Methods
    public function markAsRead()
    {
        $this->is_read = true;
        $this->read_at = now();
        $this->save();
        
        return $this;
    }

    public function getIcon()
    {
        $icons = [
            self::TYPE_APPROVAL_REQUEST => 'fa-paper-plane text-primary',
            self::TYPE_APPROVAL_APPROVED => 'fa-check-circle text-success',
            self::TYPE_APPROVAL_REJECTED => 'fa-times-circle text-danger',
            self::TYPE_RATING_GIVEN => 'fa-star text-warning',
            self::TYPE_RATING_RECEIVED => 'fa-star text-info',
            self::TYPE_SYSTEM_ALERT => 'fa-bell text-secondary',
        ];
        
        return $icons[$this->type] ?? 'fa-bell text-muted';
    }
}