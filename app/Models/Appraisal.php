<?php
// app/Models/Appraisal.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\QuarterHelper;
use Illuminate\Support\Facades\DB;

class Appraisal extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_number',
        'period',
        'start_date',
        'end_date',
        'status',
        'development_needs',
        'employee_comments',
        'supervisor_comments',
        'total_weight',
        'self_score',
        'supervisor_score',
        'overall_score',
        'rating',
        'approved_by',
        'approved_at',
        'submitted_at', 
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'self_score' => 'decimal:2',
        'supervisor_score' => 'decimal:2',
        'overall_score' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];
    
    /**
     * Automatically set dates before creating
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($appraisal) {
            // Auto-set quarter dates if not provided
            if (!$appraisal->start_date || !$appraisal->end_date) {
                $quarterDates = QuarterHelper::getQuarterDatesForDB($appraisal->period, date('Y'));
                if ($quarterDates) {
                    $appraisal->start_date = $quarterDates['start_date'];
                    $appraisal->end_date = $quarterDates['end_date'];
                }
            }
            
            // Set submitted_at timestamp when status changes to submitted
            if ($appraisal->status === 'submitted' && !$appraisal->submitted_at) {
                $appraisal->submitted_at = now();
            }
        });
        
        static::updating(function ($appraisal) {
            // Set submitted_at timestamp when status changes to submitted
            if ($appraisal->isDirty('status') && $appraisal->status === 'submitted' && !$appraisal->submitted_at) {
                $appraisal->submitted_at = now();
            }
            
            // Set approved_at timestamp when status changes to approved
            if ($appraisal->isDirty('status') && $appraisal->status === 'approved' && !$appraisal->approved_at) {
                $appraisal->approved_at = now();
            }
        });
    }
    
    /**
     * Check if appraisal is rateable by supervisors
     */
    public function isRateable()
    {
        return in_array($this->status, ['submitted', 'in_review']);
    }
    
    /**
     * Scope to get appraisals by quarter
     */
    public function scopeByQuarter($query, $quarter, $year = null)
    {
        $year = $year ?? date('Y');
        
        return $query->where('period', $quarter)
            ->whereYear('start_date', $year);
    }
    
    /**
     * Scope to get appraisals by employee
     */
    public function scopeForEmployee($query, $employeeNumber)
    {
        return $query->where('employee_number', $employeeNumber);
    }
    
    /**
     * Scope to get submitted appraisals
     */
    public function scopeSubmitted($query)
    {
        return $query->whereIn('status', ['submitted', 'in_review', 'completed', 'approved']);
    }
    
    /**
     * Scope to get draft appraisals
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
    
    /**
     * Scope to get approved appraisals
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    /**
     * Scope to get in-review appraisals
     */
    public function scopeInReview($query)
    {
        return $query->where('status', 'in_review');
    }
    
    /**
     * Scope to get completed appraisals
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    /**
     * Scope to get missed appraisals (submitted after deadline)
     */
    public function scopeMissed($query, $year = null)
    {
        $year = $year ?? date('Y');
        
        return $query->where(function($q) use ($year) {
            $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
            foreach ($quarters as $quarter) {
                $dueDate = QuarterHelper::getDueDateForQuarter($quarter, $year);
                if ($dueDate) {
                    $q->orWhere(function($q2) use ($quarter, $dueDate, $year) {
                        $q2->where('period', $quarter)
                           ->whereYear('start_date', $year)
                           ->where('created_at', '>', $dueDate);
                    });
                }
            }
        });
    }
    
    /**
     * Scope to get on-time appraisals
     */
    public function scopeOnTime($query, $year = null)
    {
        $year = $year ?? date('Y');
        
        return $query->where(function($q) use ($year) {
            $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
            foreach ($quarters as $quarter) {
                $dueDate = QuarterHelper::getDueDateForQuarter($quarter, $year);
                if ($dueDate) {
                    $q->orWhere(function($q2) use ($quarter, $dueDate, $year) {
                        $q2->where('period', $quarter)
                           ->whereYear('start_date', $year)
                           ->where('created_at', '<=', $dueDate);
                    });
                }
            }
        });
    }
    
    /**
     * Scope to get appraisals for specific year
     */
    public function scopeForYear($query, $year)
    {
        return $query->whereYear('start_date', $year);
    }
    
    /**
     * Check if this appraisal was submitted late
     */
    public function getIsLateAttribute()
    {
        if (!$this->created_at) {
            return false;
        }
        
        $year = date('Y', strtotime($this->start_date));
        $dueDate = QuarterHelper::getDueDateForQuarter($this->period, $year);
        return $dueDate && $this->created_at->gt($dueDate);
    }
    
    /**
     * Get the quarter year
     */
    public function getQuarterYearAttribute()
    {
        return date('Y', strtotime($this->start_date));
    }
    
    /**
     * Get the submission status with color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'in_review' => 'yellow',
            'completed' => 'green',
            'approved' => 'purple',
            'archived' => 'gray',
            default => 'gray'
        };
    }
    
    /**
     * Get the rating with color
     */
    public function getRatingColorAttribute()
    {
        return match(strtolower($this->rating ?? '')) {
            'excellent', 'outstanding' => 'green',
            'good', 'above average' => 'blue',
            'satisfactory', 'average' => 'yellow',
            'needs improvement', 'below average' => 'orange',
            'poor', 'unsatisfactory' => 'red',
            default => 'gray'
        };
    }
    
    /**
     * Get the user (employee) for this appraisal
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_number', 'employee_number');
    }
    
    /**
     * Get KPA entries for this appraisal
     */
    public function kpas()
    {
        return $this->hasMany(AppraisalKpa::class)->orderBy('order');
    }
    
    /**
     * Get the supervisor who approved this appraisal
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'employee_number');
    }
    
    /**
     * Get comments for this appraisal
     */
    public function comments()
    {
        return $this->hasMany(AppraisalComment::class)->orderBy('created_at', 'desc');
    }
    
    /**
     * Get supervisor comments
     */
    public function supervisorComments()
    {
        return $this->comments()->where('comment_type', 'supervisor');
    }
    
    /**
     * Get employee comments
     */
    public function employeeComments()
    {
        return $this->comments()->where('comment_type', 'employee');
    }
    
    /**
     * Get HR comments
     */
    public function hrComments()
    {
        return $this->comments()->where('comment_type', 'hr');
    }
    
    /**
     * Get weighted average score from multiple supervisors
     */
    public function getWeightedSupervisorScore()
    {
        $totalWeightedScore = 0;
        
        foreach ($this->kpas as $kpa) {
            $totalWeightedScore += $kpa->getSupervisorWeightedScoreAttribute();
        }
        
        return round($totalWeightedScore, 2);
    }

    /**
     * Get rating completion percentage from all supervisors
     */
    public function getRatingCompletionPercentage()
    {
        $employee = $this->user;
        if (!$employee) return 0;
        
        $ratingSupervisors = $employee->ratingSupervisors ?? collect();
        if ($ratingSupervisors->isEmpty()) return 0;
        
        $totalPossibleRatings = $ratingSupervisors->count() * $this->kpas->count();
        $actualRatings = 0;
        
        foreach ($this->kpas as $kpa) {
            foreach ($ratingSupervisors as $supervisor) {
                if ($kpa->hasSupervisorRating($supervisor->employee_number)) {
                    $actualRatings++;
                }
            }
        }
        
        return $totalPossibleRatings > 0 ? round(($actualRatings / $totalPossibleRatings) * 100, 2) : 0;
    }

    /**
     * Get supervisor ratings summary
     */
    public function getSupervisorRatingsSummary()
    {
        $employee = $this->user;
        if (!$employee) return [];
        
        $summary = [];
        $ratingSupervisors = $employee->ratingSupervisors ?? collect();
        
        foreach ($ratingSupervisors as $supervisor) {
            $ratedCount = 0;
            $totalRating = 0;
            
            foreach ($this->kpas as $kpa) {
                $rating = $kpa->getSupervisorRating($supervisor->employee_number);
                if ($rating && $rating->rating) {
                    $ratedCount++;
                    $totalRating += $rating->rating;
                }
            }
            
            $averageRating = $ratedCount > 0 ? $totalRating / $ratedCount : 0;
            
            $summary[] = [
                'supervisor' => $supervisor,
                'rated_count' => $ratedCount,
                'total_kpas' => $this->kpas->count(),
                'average_rating' => round($averageRating, 2),
                'weight' => $supervisor->pivot->rating_weight ?? 100,
                'is_primary' => $supervisor->pivot->is_primary ?? false,
                'relationship_type' => $supervisor->pivot->relationship_type ?? 'direct',
            ];
        }
        
        return $summary;
    }

    /**
     * Get all supervisor ratings for this appraisal
     */
    public function getAllSupervisorRatings()
    {
        $ratings = collect();
        
        foreach ($this->kpas as $kpa) {
            foreach ($kpa->ratedSupervisors() as $rating) {
                $ratings->push($rating);
            }
        }
        
        return $ratings->unique('id');
    }
    // app/Http/Controllers/SupervisorDashboardController.php or AppraisalController.php

public function supervisorDashboard()
{
    $supervisor = Auth::user();
    
    if (!$supervisor->isSupervisor()) {
        abort(403, 'Only supervisors can access this page.');
    }

    // Get employees this supervisor can rate
    $employeeNumbers = DB::table('employee_rating_supervisors')
        ->where('supervisor_id', $supervisor->employee_number)
        ->whereNull('kpa_id')
        ->pluck('employee_number');
    
    // Get pending appraisals for these employees
    $pendingAppraisals = Appraisal::with(['user', 'kpas'])
        ->whereIn('employee_number', $employeeNumbers)
        ->where('status', 'submitted')
        ->orderBy('updated_at', 'desc')
        ->get()
        ->map(function($appraisal) use ($supervisor) {
            // Calculate rating progress for this supervisor
            $totalKpas = $appraisal->kpas->count();
            $ratedKpas = 0;
            
            foreach ($appraisal->kpas as $kpa) {
                if ($kpa->hasSupervisorRating($supervisor->employee_number)) {
                    $ratedKpas++;
                }
            }
            
            // Get supervisor relationship details
            $relationship = DB::table('employee_rating_supervisors')
                ->where('employee_number', $appraisal->employee_number)
                ->where('supervisor_id', $supervisor->employee_number)
                ->whereNull('kpa_id')
                ->first();
            
            $appraisal->rating_progress = $totalKpas > 0 ? round(($ratedKpas / $totalKpas) * 100, 0) : 0;
            $appraisal->rated_kpas = $ratedKpas;
            $appraisal->total_kpas = $totalKpas;
            $appraisal->rating_weight = $relationship->rating_weight ?? 100;
            $appraisal->is_primary = $relationship->is_primary ?? false;
            
            return $appraisal;
        });

    // Get approved appraisals
    $approvedAppraisals = Appraisal::with(['user'])
        ->whereIn('employee_number', $employeeNumbers)
        ->where('status', 'approved')
        ->orderBy('updated_at', 'desc')
        ->get();

    // Get team members
    $team = User::whereIn('employee_number', $employeeNumbers)
        ->where('user_type', 'employee')
        ->orderBy('name')
        ->get()
        ->map(function($member) use ($supervisor) {
            // Get pending appraisals for this member
            $memberPending = Appraisal::where('employee_number', $member->employee_number)
                ->where('status', 'submitted')
                ->count();
            
            // Calculate average score
            $approvedMemberAppraisals = Appraisal::with('kpas')
                ->where('employee_number', $member->employee_number)
                ->where('status', 'approved')
                ->get();
            
            $finalScore = 0;
            if ($approvedMemberAppraisals->count() > 0) {
                $totalScore = 0;
                foreach ($approvedMemberAppraisals as $appraisal) {
                    $score = 0;
                    $totalSupervisorScore = 0;
                    $totalWeight = 0;
                    foreach ($appraisal->kpas as $kpa) {
                        $rating = $kpa->getFinalSupervisorRatingAttribute();
                        $totalSupervisorScore += ($rating * $kpa->weight) / 100;
                        $totalWeight += $kpa->weight;
                    }
                    $score = $totalWeight > 0 ? $totalSupervisorScore : 0;
                    $totalScore += $score;
                }
                $finalScore = $totalScore / $approvedMemberAppraisals->count();
            }
            
            $member->pending_count = $memberPending;
            $member->average_score = $finalScore;
            
            return $member;
        });

    // Calculate statistics
    $stats = [
        'team_size' => $team->count(),
        'pending_reviews' => $pendingAppraisals->count(),
        'avg_final_score' => $approvedAppraisals->avg('final_score') ?? 0,
    ];

    return view('supervisor.dashboard', compact(
        'supervisor',
        'team',
        'pendingAppraisals',
        'approvedAppraisals',
        'stats'
    ));
}
}