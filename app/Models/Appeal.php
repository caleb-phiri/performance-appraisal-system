<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_number',
        'title',
        'description',
        'status',
        'appraisal_period',
        'resolution_notes',
        'reviewed_by',
        'resolved_by',
        'review_started_at',
        'resolved_at'
    ];

    protected $casts = [
        'review_started_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the user (employee) associated with this appeal
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_number', 'employee_number');
    }

    /**
     * Get the user (reviewer) who reviewed the appeal
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'employee_number');
    }

    /**
     * Get the user (resolver) who resolved the appeal
     */
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by', 'employee_number');
    }

    /**
     * Get all comments for this appeal
     */
    public function comments()
    {
        return $this->hasMany(AppealComment::class);
    }
}