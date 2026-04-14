<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'employee_number';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'employee_number',
        'name',
        'email',
        'password',
        'user_type',
        'job_title',
        'department',
        'manager_id',
        'workstation_type',
        'hq_department',
        'toll_plaza',
        'num_employees',
        
        // Onboarding fields
        'onboarded', // from first version (keep as alias for backward compatibility)
        'is_onboarded', // from second version
        'onboarded_at',
        'password_setup_skipped',
        
        // Activity tracking
        'active', // from first version (keep as alias for backward compatibility)
        'is_active', // from second version
        'last_activity',
        'attendance_score',
        'task_completion_rate',
        
        // Left company fields
        'left_company',
        'left_at',
        'left_reason',
        'left_notes',
        
        // Role fields
        'role',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        
        // Onboarding casts
        'onboarded' => 'boolean',
        'is_onboarded' => 'boolean',
        'onboarded_at' => 'datetime',
        'password_setup_skipped' => 'boolean',
        
        // Activity casts
        'active' => 'boolean',
        'is_active' => 'boolean',
        'last_activity' => 'datetime',
        'attendance_score' => 'decimal:2',
        'task_completion_rate' => 'decimal:2',
        
        // Left company casts
        'left_company' => 'boolean',
        'left_at' => 'datetime',
        
        // Role casts
        'is_admin' => 'boolean',
    ];

    // ==============================================
    // FIXED PASSWORD METHODS
    // ==============================================
    
    /**
     * Check if user has a password set (regardless of format)
     */
    public function hasPassword()
    {
        return !empty($this->password) && trim($this->password) !== '';
    }
    
    /**
     * Check if password is properly hashed
     */
    public function hasHashedPassword()
    {
        if (!$this->hasPassword()) {
            return false;
        }
        
        return preg_match('/^\$2[ayb]\$.{56}$/', $this->password);
    }
    
    /**
     * Check if user requires password setup
     */
    public function requiresPasswordSetup()
    {
        return !$this->hasPassword() && !$this->password_setup_skipped;
    }
    
    /**
     * Verify if a given password matches the stored password
     */
    public function verifyPassword($password)
    {
        if (!$this->hasPassword()) {
            return false;
        }
        
        if ($this->hasHashedPassword()) {
            return \Illuminate\Support\Facades\Hash::check($password, $this->password);
        } else {
            return $password === $this->password;
        }
    }
    
    /**
     * Hash and set the password
     */
    public function setPassword($password)
    {
        $this->password = \Illuminate\Support\Facades\Hash::make($password);
        return $this;
    }
    
    /**
     * Convert plain text password to hash (if needed)
     */
    public function convertToHash()
    {
        if ($this->hasPassword() && !$this->hasHashedPassword()) {
            $this->password = \Illuminate\Support\Facades\Hash::make($this->password);
            $this->save();
        }
        return $this;
    }

    // ==============================================
    // AUTHENTICATION METHODS (Required for Laravel Auth)
    // ==============================================
    
    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'employee_number';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the column name for the "remember me" token.
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    // ==============================================
    // RELATIONSHIPS
    // ==============================================

    /**
     * Get direct supervisor (manager)
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'employee_number');
    }

    /**
     * Get direct supervisor alias (for backward compatibility)
     */
    public function directSupervisor()
    {
        return $this->belongsTo(User::class, 'manager_id', 'employee_number')
            ->where('user_type', 'supervisor')
            ->activeCompany();
    }

    /**
     * Relationship to subordinates
     */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id', 'employee_number')
            ->activeCompany();
    }

    /**
     * Get all rating supervisors (many-to-many)
     */
    public function ratingSupervisors()
    {
        return $this->belongsToMany(
            User::class,
            'employee_rating_supervisors',
            'employee_number', // Foreign key on pivot table for employee
            'supervisor_id', // Foreign key on pivot table for supervisor
            'employee_number', // Local key on users table
            'employee_number' // Local key on related users table
        )
        ->where('users.user_type', 'supervisor')
        ->where('users.left_company', false)
        ->wherePivotNull('kpa_id') // Only relationship records, not KPA ratings
        ->withPivot([
            'relationship_type',
            'rating_weight',
            'is_primary',
            'can_view_appraisal',
            'can_approve_appraisal',
            'notes'
        ])
        ->withTimestamps();
    }

    /**
     * Get all employees this user can rate (as a rating supervisor)
     */
    public function rateableEmployees()
    {
        return $this->belongsToMany(
            User::class,
            'employee_rating_supervisors',
            'supervisor_id', // Foreign key on pivot table for supervisor
            'employee_number', // Foreign key on pivot table for employee
            'employee_number', // Local key on users table
            'employee_number' // Local key on related users table
        )
        ->where('users.user_type', 'employee')
        ->where('users.left_company', false)
        ->wherePivotNull('kpa_id') // Only relationship records
        ->withPivot([
            'relationship_type',
            'rating_weight',
            'is_primary',
            'can_view_appraisal',
            'can_approve_appraisal',
            'notes'
        ])
        ->withTimestamps();
    }

    /**
     * Get primary supervisor
     */
    public function primarySupervisor()
    {
        return $this->ratingSupervisors()
            ->wherePivot('is_primary', true)
            ->first();
    }

    /**
     * Approval System Relationships
     */
    public function approvalRequests()
    {
        return $this->hasMany(ApprovalRequest::class, 'employee_number', 'employee_number');
    }

    public function supervisorApprovals()
    {
        return $this->hasMany(ApprovalRequest::class, 'supervisor_number', 'employee_number');
    }

    public function ratingsReceived()
    {
        return $this->hasMany(EmployeeRating::class, 'employee_number', 'employee_number');
    }

    public function ratingsGiven()
    {
        return $this->hasMany(EmployeeRating::class, 'supervisor_number', 'employee_number');
    }

    public function notifications()
    {
        return $this->hasMany(NotificationLog::class, 'employee_number', 'employee_number');
    }

    // Appraisals Relationship
    public function appraisals()
    {
        return $this->hasMany(Appraisal::class, 'employee_number', 'employee_number');
    }

    // ==============================================
    // HELPER METHODS
    // ==============================================

    /**
     * Check if user can rate a specific employee
     */
    public function canRateEmployee($employeeNumber)
    {
        if ($this->user_type !== 'supervisor') {
            return false;
        }

        // Use a direct query to avoid ambiguous column names
        return \Illuminate\Support\Facades\DB::table('employee_rating_supervisors')
            ->where('supervisor_id', $this->employee_number)
            ->where('employee_number', $employeeNumber)
            ->whereNull('kpa_id') // Only relationship records
            ->exists();
    }

    /**
     * Check if user can approve requests
     */
    public function canApproveRequests()
    {
        return $this->isSupervisor() || $this->hasSubordinates();
    }

    /**
     * Check if user is employee
     */
    public function isEmployee()
    {
        return $this->user_type === 'employee';
    }

    /**
     * Check if user has subordinates
     */
    public function hasSubordinates()
    {
        return $this->subordinates()->count() > 0;
    }

    /**
     * Check if user is active (not left company)
     */
    public function isActive(): bool
    {
        return !$this->left_company && ($this->active ?? true) && ($this->is_active ?? true);
    }

    /**
     * Check if user has left the company
     */
    public function hasLeftCompany()
    {
        return $this->left_company === true;
    }

    /**
     * Check if user is onboarded
     */
    public function isOnboarded()
    {
        return $this->onboarded || $this->is_onboarded;
    }

    /**
     * Check if user can skip password setup
     */
    public function canSkipPasswordSetup()
    {
        return $this->isOnboarded() && !$this->hasPassword();
    }

    /**
     * Check if user should be redirected to password setup
     */
    public function shouldRedirectToPasswordSetup()
    {
        return $this->requiresPasswordSetup() && !request()->is('profile/password*');
    }

    /**
     * Mark password setup as skipped
     */
    public function skipPasswordSetup()
    {
        $this->password_setup_skipped = true;
        return $this->save();
    }

    /**
     * Reset password skip status
     */
    public function resetPasswordSkip()
    {
        $this->password_setup_skipped = false;
        return $this->save();
    }

    /**
     * Update last activity
     */
    public function updateLastActivity()
    {
        $this->last_activity = now();
        $this->save();
    }

    /**
     * Get user's full display name with employee number
     */
    public function getDisplayName()
    {
        return $this->name . ' (' . $this->employee_number . ')';
    }

    /**
     * Get formatted left date
     */
    public function getLeftDateFormatted()
    {
        return $this->left_at ? $this->left_at->format('M d, Y') : 'N/A';
    }

    /**
     * Get formatted left reason
     */
    public function getLeftReasonFormatted()
    {
        if (!$this->left_reason) {
            return 'N/A';
        }
        
        $reasons = [
            'resignation' => 'Resignation',
            'termination' => 'Termination',
            'retirement' => 'Retirement',
            'end_of_contract' => 'End of Contract',
            'transfer' => 'Transfer to Another Company',
            'other' => 'Other',
        ];
        
        return $reasons[$this->left_reason] ?? ucfirst(str_replace('_', ' ', $this->left_reason));
    }

    /**
     * Get pending approvals count
     */
    public function getPendingApprovalsCount()
    {
        return $this->supervisorApprovals()->where('status', 'pending')->count();
    }

    /**
     * Get average rating
     */
    public function getAverageRating($category = null)
    {
        $query = $this->ratingsReceived();
        
        if ($category) {
            $query->where('category', $category);
        }
        
        return $query->avg('rating') ?? 0;
    }

    /**
     * Get overall rating
     */
    public function getOverallRating()
    {
        $categories = ['performance', 'attendance', 'teamwork', 'initiative', 'quality'];
        $total = 0;
        $count = 0;
        
        foreach ($categories as $category) {
            $rating = $this->getAverageRating($category);
            if ($rating > 0) {
                $total += $rating;
                $count++;
            }
        }
        
        return $count > 0 ? round($total / $count, 2) : 0;
    }

    /**
     * Get recent ratings
     */
    public function getRecentRatings($limit = 5)
    {
        return $this->ratingsReceived()
            ->with('supervisor')
            ->orderBy('rating_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCount()
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    /**
     * Get unread notifications
     */
    public function getUnreadNotifications()
    {
        return $this->notifications()
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get notifications
     */
    public function getNotifications($limit = 20)
    {
        return $this->notifications()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get workstation display
     */
    public function getWorkstationDisplay()
    {
        if ($this->workstation_type === 'hq') {
            return "HQ - " . ($this->hq_department ? ucfirst(str_replace('hq_', '', $this->hq_department)) : 'N/A');
        } elseif ($this->workstation_type === 'toll_plaza') {
            return "Toll Plaza - " . ($this->toll_plaza ?? 'N/A');
        }
        
        return 'N/A';
    }

    /**
     * Get department display
     */
    public function getDepartmentDisplay()
    {
        return ucfirst($this->department);
    }

    /**
     * Get leave balance
     */
    public function getLeaveBalance($type = 'annual')
    {
        $defaultBalances = [
            'annual' => 21,
            'sick' => 14,
            'emergency' => 5,
        ];
        
        return $defaultBalances[$type] ?? 0;
    }

    /**
     * Get pending leave requests
     */
    public function getPendingLeaveRequests()
    {
        return $this->approvalRequests()
            ->where('type', 'leave')
            ->where('status', 'pending')
            ->get();
    }

    /**
     * Get approved leave days
     */
    public function getApprovedLeaveDays($year = null)
    {
        if (!$year) {
            $year = now()->year;
        }
        
        return $this->approvalRequests()
            ->where('type', 'leave')
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->sum('duration') ?? 0;
    }

    /**
     * Get team performance summary (for supervisors)
     */
    public function getTeamPerformance()
    {
        if (!$this->isSupervisor()) {
            return [];
        }
        
        return $this->subordinates()
            ->withAvg('ratingsReceived', 'rating')
            ->withCount(['approvalRequests' => function($query) {
                $query->where('status', 'pending');
            }])
            ->get()
            ->map(function($employee) {
                return [
                    'name' => $employee->name,
                    'employee_number' => $employee->employee_number,
                    'job_title' => $employee->job_title,
                    'avg_rating' => $employee->ratings_received_avg_rating ?? 0,
                    'pending_requests' => $employee->approval_requests_count,
                    'attendance_score' => $employee->attendance_score ?? 0,
                    'task_completion' => $employee->task_completion_rate ?? 0,
                ];
            });
    }

    // ==============================================
    // SCOPES
    // ==============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('left_company', false);
    }

    public function scopeSupervisors($query)
    {
        return $query->where('user_type', 'supervisor');
    }

    public function scopeEmployees($query)
    {
        return $query->where('user_type', 'employee');
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByWorkstation($query, $workstationType, $value = null)
    {
        $query = $query->where('workstation_type', $workstationType);
        
        if ($value) {
            if ($workstationType === 'hq') {
                $query->where('hq_department', $value);
            } elseif ($workstationType === 'toll_plaza') {
                $query->where('toll_plaza', $value);
            }
        }
        
        return $query;
    }

    public function scopeOnboarded($query)
    {
        return $query->where(function($q) {
            $q->where('onboarded', true)
              ->orWhere('is_onboarded', true);
        });
    }

    public function scopeNotOnboarded($query)
    {
        return $query->where(function($q) {
            $q->where('onboarded', false)
              ->where('is_onboarded', false);
        });
    }
    
    public function scopeLeftCompany($query)
    {
        return $query->where('left_company', true);
    }
    
    public function scopeActiveCompany($query)
    {
        return $query->where('left_company', false);
    }

    // ==============================================
    // ROLE-BASED METHODS (Single, enhanced version)
    // ==============================================

    /**
     * Enhanced isSupervisor method with multiple checks
     * This is the ONLY isSupervisor method in the class
     */
    public function isSupervisor()
    {
        // Check by user_type first (most common)
        if ($this->user_type === 'supervisor') {
            return true;
        }
        
        // Check by role field if it exists
        if (isset($this->role) && in_array($this->role, ['supervisor', 'admin', 'manager'])) {
            return true;
        }
        
        // Check by is_admin flag
        if (isset($this->is_admin) && $this->is_admin) {
            return true;
        }
        
        // Check by department (management departments)
        $managementDepartments = ['Management', 'HR', 'Administration', 'Executive'];
        if (in_array($this->department, $managementDepartments)) {
            return true;
        }
        
        // Check if user has subordinates (practical supervisor check)
        if ($this->subordinates()->count() > 0) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->is_admin ?? false;
    }

    /**
     * Check if user is HR
     */
    public function isHr()
    {
        return $this->department === 'HR' || $this->user_type === 'hr';
    }

    /**
     * Check if user is manager
     */
    public function isManager()
    {
        return $this->role === 'manager' || $this->user_type === 'manager';
    }
    /**
 * Get quarterly self score for an employee
 */
public function getQuarterlySelfScore($quarter, $year)
{
    $dates = $this->getQuarterDateRange($quarter, $year);
    
    $appraisals = $this->appraisals()
        ->whereBetween('created_at', [$dates['start'], $dates['end']])
        ->with('kpas')
        ->get();
    
    if ($appraisals->isEmpty()) {
        return 0;
    }
    
    $totalScore = 0;
    $count = 0;
    
    foreach ($appraisals as $appraisal) {
        $score = $this->calculateAppraisalSelfScore($appraisal);
        if ($score > 0) {
            $totalScore += $score;
            $count++;
        }
    }
    
    return $count > 0 ? round($totalScore / $count, 1) : 0;
}

/**
 * Get quarterly manager score for an employee
 */
public function getQuarterlyManagerScore($quarter, $year)
{
    $dates = $this->getQuarterDateRange($quarter, $year);
    
    $appraisals = $this->appraisals()
        ->whereBetween('created_at', [$dates['start'], $dates['end']])
        ->with('kpas')
        ->get();
    
    if ($appraisals->isEmpty()) {
        return 0;
    }
    
    $totalScore = 0;
    $count = 0;
    
    foreach ($appraisals as $appraisal) {
        $score = $this->calculateAppraisalManagerScore($appraisal);
        if ($score > 0) {
            $totalScore += $score;
            $count++;
        }
    }
    
    return $count > 0 ? round($totalScore / $count, 1) : 0;
}

/**
 * Calculate self score from appraisal
 */
private function calculateAppraisalSelfScore($appraisal)
{
    if (!$appraisal->kpas || $appraisal->kpas->isEmpty()) {
        return 0;
    }
    
    $totalWeight = 0;
    $weightedSum = 0;
    
    foreach ($appraisal->kpas as $kpa) {
        $weight = $kpa->weight ?? 0;
        $totalWeight += $weight;
        
        $selfRating = $kpa->self_rating ?? 0;
        $kpi = $kpa->kpi ?? 4;
        
        if ($selfRating > 0 && $kpi > 0) {
            $percentage = ($selfRating / $kpi) * 100;
            $weightedSum += ($percentage * $weight) / 100;
        }
    }
    
    return $totalWeight > 0 ? $weightedSum : 0;
}

/**
 * Calculate manager score from appraisal
 */
private function calculateAppraisalManagerScore($appraisal)
{
    if (!$appraisal->kpas || $appraisal->kpas->isEmpty()) {
        return 0;
    }
    
    $totalWeight = 0;
    $weightedSum = 0;
    
    foreach ($appraisal->kpas as $kpa) {
        $weight = $kpa->weight ?? 0;
        $totalWeight += $weight;
        
        $managerRating = $kpa->supervisor_rating ?? 0;
        $kpi = $kpa->kpi ?? 4;
        
        if ($managerRating > 0 && $kpi > 0) {
            $percentage = ($managerRating / $kpi) * 100;
            $weightedSum += ($percentage * $weight) / 100;
        }
    }
    
    return $totalWeight > 0 ? $weightedSum : 0;
}

/**
 * Get quarter date range
 */
private function getQuarterDateRange($quarter, $year)
{
    $quarters = [
        'Q1' => ['start' => "$year-01-01", 'end' => "$year-03-31"],
        'Q2' => ['start' => "$year-04-01", 'end' => "$year-06-30"],
        'Q3' => ['start' => "$year-07-01", 'end' => "$year-09-30"],
        'Q4' => ['start' => "$year-10-01", 'end' => "$year-12-31"],
    ];
    
    return $quarters[$quarter] ?? $quarters['Q1'];
}

/**
 * Get monthly salary attribute (if not exists)
 */
public function getMonthlySalaryAttribute($value)
{
    // If you have a salary field, use it, otherwise return a default or calculate
    return $value ?? 0;
}

/**
 * Get position attribute (alias for job_title)
 */
public function getPositionAttribute()
{
    return $this->job_title;
}

}