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

    // ==============================================
    // RELATIONSHIPS
    // ==============================================

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

    // ==============================================
    // SCOPES
    // ==============================================

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
     * Scope for primary supervisors
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope for supervisors who can view appraisals
     */
    public function scopeCanViewAppraisal($query)
    {
        return $query->where('can_view_appraisal', true);
    }

    /**
     * Scope for supervisors who can approve appraisals
     */
    public function scopeCanApproveAppraisal($query)
    {
        return $query->where('can_approve_appraisal', true);
    }

    /**
     * Scope for specific employee
     */
    public function scopeForEmployee($query, $employeeNumber)
    {
        return $query->where('employee_number', $employeeNumber);
    }

    /**
     * Scope for specific supervisor
     */
    public function scopeForSupervisor($query, $supervisorId)
    {
        return $query->where('supervisor_id', $supervisorId);
    }

    /**
     * Scope for specific KPA
     */
    public function scopeForKpa($query, $kpaId)
    {
        return $query->where('kpa_id', $kpaId);
    }

    /**
     * Scope for completed KPA ratings
     */
    public function scopeCompletedRatings($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending KPA ratings
     */
    public function scopePendingRatings($query)
    {
        return $query->where('status', 'pending');
    }

    // ==============================================
    // HELPER METHODS
    // ==============================================

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
     * Mark KPA rating as completed
     */
    public function markAsCompleted($rating, $comments = null)
    {
        if (!$this->isKpaRating()) {
            return false;
        }

        $this->rating = $rating;
        $this->comments = $comments;
        $this->status = 'completed';
        $this->rated_at = now();
        
        return $this->save();
    }

    /**
     * Get formatted relationship type
     */
    public function getRelationshipTypeFormatted()
    {
        if (!$this->relationship_type) {
            return 'Not Specified';
        }

        $types = [
            'direct' => 'Direct Supervisor',
            'matrix' => 'Matrix Supervisor',
            'functional' => 'Functional Manager',
            'project' => 'Project Manager',
            'peer' => 'Peer Reviewer',
            'other' => 'Other'
        ];

        return $types[$this->relationship_type] ?? ucfirst($this->relationship_type);
    }

    /**
     * Get rating status badge class
     */
    public function getRatingStatusBadgeClass()
    {
        if (!$this->isKpaRating()) {
            return 'bg-info';
        }

        switch ($this->status) {
            case 'completed':
                return 'bg-success';
            case 'pending':
                return 'bg-warning';
            case 'draft':
                return 'bg-secondary';
            case 'rejected':
                return 'bg-danger';
            default:
                return 'bg-info';
        }
    }

    /**
     * Get rating status text
     */
    public function getRatingStatusText()
    {
        if (!$this->isKpaRating()) {
            return 'Relationship';
        }

        return ucfirst($this->status) ?? 'Pending';
    }

    /**
     * Get formatted rating (for display)
     */
    public function getFormattedRating()
    {
        if (!$this->rating || !$this->isKpaRating()) {
            return 'N/A';
        }

        // Assuming rating is out of 5
        return number_format($this->rating, 1) . '/5.0';
    }

    /**
     * Get rating percentage
     */
    public function getRatingPercentage()
    {
        if (!$this->rating || !$this->isKpaRating()) {
            return 0;
        }

        // Convert 5-point scale to percentage
        return ($this->rating / 5) * 100;
    }

    /**
     * Check if supervisor can rate KPA
     */
    public function canRateKpa($kpaId)
    {
        // Check if supervisor has permission to rate this employee's KPAs
        return $this->where('employee_number', $this->employee_number)
            ->where('supervisor_id', $this->supervisor_id)
            ->whereNotNull('can_view_appraisal')
            ->exists();
    }

    /**
     * Create a new KPA rating record
     */
    public static function createKpaRating($kpaId, $supervisorId, $data = [])
    {
        // Get employee number from KPA
        $kpa = AppraisalKpa::find($kpaId);
        if (!$kpa) {
            return null;
        }

        $appraisal = $kpa->appraisal;
        if (!$appraisal) {
            return null;
        }

        return self::create(array_merge([
            'kpa_id' => $kpaId,
            'supervisor_id' => $supervisorId,
            'employee_number' => $appraisal->employee_number,
            'status' => 'pending',
            'rated_at' => null
        ], $data));
    }

    /**
     * Get all KPA ratings for an employee by a specific supervisor
     */
    public static function getEmployeeKpaRatings($employeeNumber, $supervisorId, $appraisalId = null)
    {
        $query = self::kpaRatings()
            ->where('employee_number', $employeeNumber)
            ->where('supervisor_id', $supervisorId);

        if ($appraisalId) {
            $query->whereHas('kpa', function($q) use ($appraisalId) {
                $q->where('appraisal_id', $appraisalId);
            });
        }

        return $query->get();
    }

    /**
     * Get average KPA rating by supervisor for an employee
     */
    public static function getAverageKpaRating($employeeNumber, $supervisorId, $appraisalId = null)
    {
        $ratings = self::getEmployeeKpaRatings($employeeNumber, $supervisorId, $appraisalId)
            ->where('status', 'completed');

        if ($ratings->isEmpty()) {
            return null;
        }

        return $ratings->avg('rating');
    }

    /**
     * Get pending KPA ratings for a supervisor
     */
    public static function getPendingKpaRatingsForSupervisor($supervisorId)
    {
        return self::kpaRatings()
            ->pendingRatings()
            ->where('supervisor_id', $supervisorId)
            ->with(['kpa', 'employee'])
            ->get();
    }

    /**
     * Get all supervisors for an employee with their KPA rating stats
     */
    public static function getSupervisorsWithStats($employeeNumber, $appraisalId = null)
    {
        $supervisors = self::relationships()
            ->forEmployee($employeeNumber)
            ->with(['supervisor'])
            ->get();

        return $supervisors->map(function($relationship) use ($employeeNumber, $appraisalId) {
            $supervisor = $relationship->supervisor;
            $avgRating = self::getAverageKpaRating($employeeNumber, $supervisor->employee_number, $appraisalId);
            $pendingCount = self::kpaRatings()
                ->pendingRatings()
                ->forEmployee($employeeNumber)
                ->forSupervisor($supervisor->employee_number)
                ->count();

            return [
                'relationship' => $relationship,
                'supervisor' => $supervisor,
                'average_rating' => $avgRating,
                'pending_ratings' => $pendingCount,
                'can_rate' => $relationship->can_view_appraisal,
                'can_approve' => $relationship->can_approve_appraisal,
                'is_primary' => $relationship->is_primary
            ];
        });
    }
}