<?php
// app/Models/EmployeeRatingSupervisor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRatingSupervisor extends Model
{
    use HasFactory;

    protected $table = 'employee_rating_supervisors';

    protected $fillable = [
        'employee_number',
        'supervisor_id',
        'relationship_type',
        'rating_weight',
        'is_primary',
        'can_view_appraisal',
        'can_approve_appraisal',
        'notes',
        'kpa_id',
        'rating',
        'comments',
        'status',
        'rated_at',
    ];

    protected $casts = [
        'rating_weight' => 'integer',
        'is_primary' => 'boolean',
        'can_view_appraisal' => 'boolean',
        'can_approve_appraisal' => 'boolean',
        'rating' => 'decimal:2',
        'rated_at' => 'datetime',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_number', 'employee_number');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id', 'employee_number');
    }

    public function kpa()
    {
        return $this->belongsTo(AppraisalKpa::class, 'kpa_id');
    }

    public function appraisal()
    {
        return $this->hasOneThrough(
            Appraisal::class,
            AppraisalKpa::class,
            'id',
            'id',
            'kpa_id',
            'appraisal_id'
        );
    }

    // Scopes
    public function scopeRelationships($query)
    {
        return $query->whereNull('kpa_id');
    }

    public function scopeKpaRatings($query)
    {
        return $query->whereNotNull('kpa_id');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Helper methods
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isKpaRating()
    {
        return !is_null($this->kpa_id);
    }

    public function markAsCompleted($rating, $comments = null)
    {
        $this->rating = $rating;
        $this->comments = $comments;
        $this->status = 'completed';
        $this->rated_at = now();
        
        return $this->save();
    }
}