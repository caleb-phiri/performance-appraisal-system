<?php
// app/Models/AppraisalKpa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppraisalKpa extends Model
{
    use HasFactory;

    protected $fillable = [
        'appraisal_id',
        'category',
        'kpa',
        'result_indicators',
        'kpi',
        'weight',
        'self_rating',
        'supervisor_rating',
        'comments',
        'supervisor_comments',
        'order',
    ];

    protected $casts = [
        'kpi' => 'integer',
        'weight' => 'integer',
        'self_rating' => 'integer',
        'supervisor_rating' => 'decimal:2',
        'order' => 'integer',
    ];

    // Relationships
    public function appraisal()
    {
        return $this->belongsTo(Appraisal::class);
    }

    /**
     * Get all supervisor ratings for this KPA
     */
    public function supervisorRatings()
    {
        return $this->hasMany(EmployeeRatingSupervisor::class, 'kpa_id');
    }

    /**
     * Get specific supervisor's rating
     */
    public function getSupervisorRating($supervisorNumber)
    {
        return $this->supervisorRatings()
            ->where('supervisor_id', $supervisorNumber)
            ->where('status', 'completed')
            ->first();
    }

    /**
     * Check if supervisor has rated this KPA
     */
    public function hasSupervisorRating($supervisorNumber)
    {
        return $this->supervisorRatings()
            ->where('supervisor_id', $supervisorNumber)
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Get all rated supervisors with their ratings
     */
    public function ratedSupervisors()
    {
        return $this->supervisorRatings()
            ->where('status', 'completed')
            ->with('supervisor')
            ->orderBy('rated_at', 'desc')
            ->get();
    }

    /**
     * Get weighted average rating from all supervisors
     */
    public function getWeightedAverageRating()
    {
        $ratings = $this->ratedSupervisors();
        
        if ($ratings->isEmpty()) {
            return null;
        }

        $totalWeightedRating = 0;
        $totalWeight = 0;

        foreach ($ratings as $rating) {
            $weight = $rating->rating_weight ?? 100;
            $totalWeightedRating += ($rating->rating * $weight);
            $totalWeight += $weight;
        }

        if ($totalWeight > 0) {
            return round($totalWeightedRating / $totalWeight, 2);
        }

        return null;
    }

    /**
     * Get final supervisor rating attribute (for backward compatibility)
     */
    public function getFinalSupervisorRatingAttribute()
    {
        $average = $this->getWeightedAverageRating();
        return $average ?? $this->supervisor_rating ?? 0;
    }

    /**
     * Calculate supervisor weighted score
     */
    public function getSupervisorWeightedScoreAttribute()
    {
        $finalRating = $this->getFinalSupervisorRatingAttribute();
        if (!$finalRating || !$this->weight || !$this->kpi || $this->kpi == 0) {
            return 0;
        }
        return ($finalRating / $this->kpi) * $this->weight;
    }

    /**
     * Calculate self weighted score
     */
    public function getSelfWeightedScoreAttribute()
    {
        if (!$this->self_rating || !$this->weight || !$this->kpi || $this->kpi == 0) {
            return 0;
        }
        return ($this->self_rating / $this->kpi) * $this->weight;
    }
}