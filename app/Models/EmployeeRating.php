<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRating extends Model
{
    protected $fillable = [
        'employee_number',
        'supervisor_number',
        'rating',
        'comments',
        'category',
        'rating_date',
        'period',
        'metrics',
    ];

    protected $casts = [
        'rating_date' => 'date',
        'metrics' => 'array',
    ];

    // Category constants
    const CATEGORY_PERFORMANCE = 'performance';
    const CATEGORY_ATTENDANCE = 'attendance';
    const CATEGORY_TEAMWORK = 'teamwork';
    const CATEGORY_INITIATIVE = 'initiative';
    const CATEGORY_QUALITY = 'quality';

    // Period constants
    const PERIOD_DAILY = 'daily';
    const PERIOD_WEEKLY = 'weekly';
    const PERIOD_MONTHLY = 'monthly';
    const PERIOD_QUARTERLY = 'quarterly';

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
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    public function scopeForEmployee($query, $employeeNumber)
    {
        return $query->where('employee_number', $employeeNumber);
    }

    public function scopeBySupervisor($query, $supervisorNumber)
    {
        return $query->where('supervisor_number', $supervisorNumber);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('rating_date', '>=', now()->subDays($days));
    }

    // Methods
    public function getRatingStars()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getRatingText()
    {
        $ratings = [
            1 => 'Poor',
            2 => 'Fair',
            3 => 'Good',
            4 => 'Very Good',
            5 => 'Excellent'
        ];
        
        return $ratings[$this->rating] ?? 'Not Rated';
    }

    public function getCategoryIcon()
    {
        $icons = [
            self::CATEGORY_PERFORMANCE => 'fa-chart-line',
            self::CATEGORY_ATTENDANCE => 'fa-calendar-check',
            self::CATEGORY_TEAMWORK => 'fa-users',
            self::CATEGORY_INITIATIVE => 'fa-lightbulb',
            self::CATEGORY_QUALITY => 'fa-award',
        ];
        
        return $icons[$this->category] ?? 'fa-star';
    }
}