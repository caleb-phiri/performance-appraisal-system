<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorRating extends Model
{
    use HasFactory;

    protected $table = 'employee_rating_supervisors';

    protected $fillable = [
        // Fields for employee-supervisor relationship
        'employee_number',
        'supervisor_id',
        'relationship_type',
        'rating_weight',
        'is_primary',
        'can_view_appraisal',
        'can_approve_appraisal',
        'notes',
        
        // Fields for KPA-specific ratings
        'kpa_id',
        'rating',
        'comments',
        'status',
        'rated_at'
    ];

    protected $casts = [
        // Relationship casts
        'rating_weight' => 'integer',
        'is_primary' => 'boolean',
        'can_view_appraisal' => 'boolean',
        'can_approve_appraisal' => 'boolean',
        
        // Rating casts
        'rating' => 'decimal:2',
        'rated_at' => 'datetime'
    ];

    /**
     * Get the employee
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_number', 'employee_number');
    }

    /**
     * Get the supervisor
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id', 'employee_number');
    }

    /**
     * Get the KPA being rated (only applicable for KPA ratings)
     */
    public function kpa()
    {
        return $this->belongsTo(AppraisalKpa::class, 'kpa_id');
    }

    /**
     * Get the appraisal through KPA (for KPA ratings)
     */
    public function appraisal()
    {
        return $this->hasOneThrough(
            Appraisal::class,
            AppraisalKpa::class,
            'id', // Foreign key on AppraisalKpa table
            'id', // Foreign key on Appraisal table
            'kpa_id', // Local key on this table
            'appraisal_id' // Local key on AppraisalKpa table
        );
    }

    /**
     * Scope for relationship records only (without KPA ratings)
     */
    public function scopeRelationships($query)
    {
        return $query->whereNull('kpa_id');
    }

    /**
     * Scope for KPA rating records only
     */
    public function scopeKpaRatings($query)
    {
        return $query->whereNotNull('kpa_id');
    }

    /**
     * Check if this is a KPA rating record
     */
    public function isKpaRating()
    {
        return !is_null($this->kpa_id);
    }

    /**
     * Check if this is a relationship record
     */
    public function isRelationship()
    {
        return is_null($this->kpa_id);
    }

    /**
     * Check if rating is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if rating is pending
     */
    public function isPending()
    {
        return $this->status === 'pending' || is_null($this->status);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted($rating, $comments = null)
    {
        $this->rating = $rating;
        $this->comments = $comments;
        $this->status = 'completed';
        $this->rated_at = now();
        
        return $this->save();
    }
}