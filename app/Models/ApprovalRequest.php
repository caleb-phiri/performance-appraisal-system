<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalRequest extends Model
{
    use HasFactory;

    protected $table = 'approval_requests';

    protected $fillable = [
        'requestable_type',
        'requestable_id',
        'approver_number',
        'approver_name',
        'approver_role',
        'approval_level',
        'status',
        'comments',
        'actioned_at'
    ];

    protected $casts = [
        'actioned_at' => 'datetime',
        'approval_level' => 'integer'
    ];

    /**
     * Get the parent requestable model (leave, appraisal, etc.)
     */
    public function requestable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the approver user.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_number', 'employee_number');
    }

    /**
     * Scope pending approvals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope approved approvals.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope rejected approvals.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}