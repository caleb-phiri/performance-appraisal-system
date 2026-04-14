<?php
// app/Helpers/QuarterHelper.php
namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Appraisal;

class QuarterHelper
{
    /**
     * Get quarter information for a given quarter WITH GRACE PERIOD
     * Grace period extends until the 20th of the month following the quarter end
     */
    public static function getQuarterInfo($quarter = null, $year = null)
    {
        if (!$year) {
            $year = Carbon::now()->year;
        }
        
        if (!$quarter) {
            // Get current quarter based on current date (grace period aware)
            $quarter = self::getCurrentQuarterWithGrace();
        }
        
        $quarterInfo = [
            'quarter' => $quarter,
            'year' => $year,
        ];
        
        switch ($quarter) {
            case 'Q1':
                $quarterInfo['quarter_name'] = 'Quarter 1';
                $quarterInfo['quarter_months'] = 'January - March';
                $quarterInfo['appraisal_start'] = Carbon::create($year, 1, 1)->format('M d');
                $quarterInfo['appraisal_end'] = Carbon::create($year, 3, 31)->format('M d');
                $quarterInfo['grace_end'] = Carbon::create($year, 4, 20)->format('M d');
                $quarterInfo['due_date'] = Carbon::create($year, 4, 20)->format('M d');
                $quarterInfo['due_date_full'] = Carbon::create($year, 4, 20)->format('F d, Y');
                $quarterInfo['appraisal_end_full'] = Carbon::create($year, 3, 31)->format('F d, Y');
                $quarterInfo['grace_end_full'] = Carbon::create($year, 4, 20)->format('F d, Y');
                break;
            case 'Q2':
                $quarterInfo['quarter_name'] = 'Quarter 2';
                $quarterInfo['quarter_months'] = 'April - June';
                $quarterInfo['appraisal_start'] = Carbon::create($year, 4, 1)->format('M d');
                $quarterInfo['appraisal_end'] = Carbon::create($year, 6, 30)->format('M d');
                $quarterInfo['grace_end'] = Carbon::create($year, 7, 20)->format('M d');
                $quarterInfo['due_date'] = Carbon::create($year, 7, 20)->format('M d');
                $quarterInfo['due_date_full'] = Carbon::create($year, 7, 20)->format('F d, Y');
                $quarterInfo['appraisal_end_full'] = Carbon::create($year, 6, 30)->format('F d, Y');
                $quarterInfo['grace_end_full'] = Carbon::create($year, 7, 20)->format('F d, Y');
                break;
            case 'Q3':
                $quarterInfo['quarter_name'] = 'Quarter 3';
                $quarterInfo['quarter_months'] = 'July - September';
                $quarterInfo['appraisal_start'] = Carbon::create($year, 7, 1)->format('M d');
                $quarterInfo['appraisal_end'] = Carbon::create($year, 9, 30)->format('M d');
                $quarterInfo['grace_end'] = Carbon::create($year, 10, 20)->format('M d');
                $quarterInfo['due_date'] = Carbon::create($year, 10, 20)->format('M d');
                $quarterInfo['due_date_full'] = Carbon::create($year, 10, 20)->format('F d, Y');
                $quarterInfo['appraisal_end_full'] = Carbon::create($year, 9, 30)->format('F d, Y');
                $quarterInfo['grace_end_full'] = Carbon::create($year, 10, 20)->format('F d, Y');
                break;
            case 'Q4':
                $quarterInfo['quarter_name'] = 'Quarter 4';
                $quarterInfo['quarter_months'] = 'October - December';
                $quarterInfo['appraisal_start'] = Carbon::create($year, 10, 1)->format('M d');
                $quarterInfo['appraisal_end'] = Carbon::create($year, 12, 31)->format('M d');
                $quarterInfo['grace_end'] = Carbon::create($year + 1, 1, 20)->format('M d');
                $quarterInfo['due_date'] = Carbon::create($year + 1, 1, 20)->format('M d');
                $quarterInfo['due_date_full'] = Carbon::create($year + 1, 1, 20)->format('F d, Y');
                $quarterInfo['appraisal_end_full'] = Carbon::create($year, 12, 31)->format('F d, Y');
                $quarterInfo['grace_end_full'] = Carbon::create($year + 1, 1, 20)->format('F d, Y');
                break;
        }
        
        // Determine if quarter is past, current (including grace), or future
        $now = Carbon::now();
        
        $dueDateYear = ($quarter === 'Q4') ? $year + 1 : $year;
        $appraisalEndYear = $year;
        
        $dueDate = Carbon::createFromFormat('M d Y', $quarterInfo['due_date'] . ' ' . $dueDateYear);
        $appraisalEnd = Carbon::createFromFormat('M d Y', $quarterInfo['appraisal_end'] . ' ' . $appraisalEndYear);
        
        // Check if currently in grace period
        $isInGrace = $now->gt($appraisalEnd) && $now->lte($dueDate);
        
        if ($now->gt($dueDate)) {
            $quarterInfo['is_past'] = true;
            $quarterInfo['is_current'] = false;
            $quarterInfo['is_future'] = false;
            $quarterInfo['is_in_grace'] = false;
        } elseif ($now->gte($appraisalEnd) && $now->lte($dueDate)) {
            $quarterInfo['is_past'] = false;
            $quarterInfo['is_current'] = true;
            $quarterInfo['is_future'] = false;
            $quarterInfo['is_in_grace'] = $isInGrace;
        } else {
            $quarterInfo['is_past'] = false;
            $quarterInfo['is_current'] = false;
            $quarterInfo['is_future'] = true;
            $quarterInfo['is_in_grace'] = false;
        }
        
        // Add grace period status text
        if ($quarterInfo['is_in_grace']) {
            $daysLeft = $now->diffInDays($dueDate, false);
            $quarterInfo['grace_status_text'] = "Grace Period - {$daysLeft} day(s) remaining";
            $quarterInfo['days_left_in_grace'] = $daysLeft;
        } else {
            $quarterInfo['grace_status_text'] = null;
            $quarterInfo['days_left_in_grace'] = 0;
        }
        
        return $quarterInfo;
    }
    
    /**
     * Get quarter for a specific date (grace period aware)
     * If date is within grace period, returns the quarter that just ended
     */
    public static function getQuarterForDate($date)
    {
        $date = Carbon::parse($date);
        $year = $date->year;
        
        // Check grace periods first
        $gracePeriods = [
            'Q1' => ['start' => $year . '-01-01', 'grace_end' => $year . '-04-20'],
            'Q2' => ['start' => $year . '-04-01', 'grace_end' => $year . '-07-20'],
            'Q3' => ['start' => $year . '-07-01', 'grace_end' => $year . '-10-20'],
            'Q4' => ['start' => $year . '-10-01', 'grace_end' => ($year + 1) . '-01-20'],
        ];
        
        foreach ($gracePeriods as $quarter => $dates) {
            $startDate = Carbon::parse($dates['start']);
            $graceEnd = Carbon::parse($dates['grace_end']);
            
            if ($date->between($startDate, $graceEnd)) {
                return $quarter;
            }
        }
        
        // Fallback to simple month-based quarter detection
        $month = $date->month;
        if ($month >= 1 && $month <= 3) {
            return 'Q1';
        } elseif ($month >= 4 && $month <= 6) {
            return 'Q2';
        } elseif ($month >= 7 && $month <= 9) {
            return 'Q3';
        } else {
            return 'Q4';
        }
    }
    
    /**
     * Get quarter start and end dates for database (actual quarter dates, not including grace)
     */
    public static function getQuarterDatesForDB($quarter, $year)
    {
        switch ($quarter) {
            case 'Q1':
                return [
                    'start_date' => Carbon::create($year, 1, 1)->format('Y-m-d'),
                    'end_date' => Carbon::create($year, 3, 31)->format('Y-m-d'),
                ];
            case 'Q2':
                return [
                    'start_date' => Carbon::create($year, 4, 1)->format('Y-m-d'),
                    'end_date' => Carbon::create($year, 6, 30)->format('Y-m-d'),
                ];
            case 'Q3':
                return [
                    'start_date' => Carbon::create($year, 7, 1)->format('Y-m-d'),
                    'end_date' => Carbon::create($year, 9, 30)->format('Y-m-d'),
                ];
            case 'Q4':
                return [
                    'start_date' => Carbon::create($year, 10, 1)->format('Y-m-d'),
                    'end_date' => Carbon::create($year, 12, 31)->format('Y-m-d'),
                ];
        }
        
        return null;
    }
    
    /**
     * Get all quarters for a year with submission status (grace period aware)
     */
    public static function getQuartersWithStatus($employeeNumber, $year = null)
    {
        $year = $year ?: Carbon::now()->year;
        $currentQuarter = self::getCurrentQuarterWithGrace();
        
        // Get all submitted appraisals for this employee and year
        $submittedAppraisals = Appraisal::where('employee_number', $employeeNumber)
            ->whereYear('start_date', $year)
            ->whereIn('status', ['submitted', 'in_review', 'completed', 'approved'])
            ->get();
        
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $result = [];
        $hasMissedQuarters = false;
        $missedQuarters = [];
        $gracePeriodQuarters = [];
        
        foreach ($quarters as $quarter) {
            $quarterInfo = self::getQuarterInfo($quarter, $year);
            $quarterDates = self::getQuarterDatesForDB($quarter, $year);
            
            // Check if there's a submission for this quarter
            $submission = $submittedAppraisals->first(function($appraisal) use ($quarter, $year) {
                return $appraisal->period === $quarter && 
                       date('Y', strtotime($appraisal->start_date)) == $year;
            });
            
            // Determine status and whether it can be submitted (grace period aware)
            $status = 'future';
            $canSubmit = false;
            $isLate = false;
            $isInGrace = false;
            
            if ($submission) {
                $status = 'completed';
                $canSubmit = false;
            } elseif ($quarterInfo['is_past']) {
                $status = 'missed';
                $canSubmit = false; // Past grace period - cannot submit
                $hasMissedQuarters = true;
                $missedQuarters[] = $quarter;
            } elseif ($quarterInfo['is_current']) {
                if ($quarterInfo['is_in_grace']) {
                    $status = 'grace';
                    $isInGrace = true;
                    $gracePeriodQuarters[] = $quarter;
                } else {
                    $status = 'current';
                }
                // Can submit current quarter (including grace period)
                $canSubmit = true;
                $isLate = $quarterInfo['is_in_grace'];
            } elseif ($quarterInfo['is_future']) {
                $status = 'future';
                $canSubmit = false;
            }
            
            $result[$quarter] = [
                'quarter' => $quarter,
                'name' => $quarterInfo['quarter_name'],
                'months' => $quarterInfo['quarter_months'],
                'due_date' => $quarterInfo['due_date'],
                'due_date_full' => $quarterInfo['due_date_full'],
                'appraisal_end_full' => $quarterInfo['appraisal_end_full'],
                'grace_end_full' => $quarterInfo['grace_end_full'],
                'status' => $status,
                'can_submit' => $canSubmit,
                'is_late' => $isLate,
                'is_in_grace' => $isInGrace,
                'days_left_in_grace' => $quarterInfo['days_left_in_grace'],
                'grace_status_text' => $quarterInfo['grace_status_text'],
                'submission' => $submission,
                'info' => $quarterInfo,
                'dates' => $quarterDates,
            ];
        }
        
        return [
            'quarters' => $result,
            'has_missed_quarters' => $hasMissedQuarters,
            'missed_quarters' => $missedQuarters,
            'grace_period_quarters' => $gracePeriodQuarters,
            'current_quarter' => $currentQuarter,
            'current_year' => $year,
            'can_submit_current' => $result[$currentQuarter]['can_submit'] ?? false,
            'is_current_in_grace' => isset($result[$currentQuarter]) && $result[$currentQuarter]['is_in_grace'],
        ];
    }
    
    /**
     * Get current quarter (grace period aware)
     * Returns the quarter that is currently open for submission
     */
    public static function getCurrentQuarterWithGrace()
    {
        $now = Carbon::now();
        $year = $now->year;
        
        // Check each quarter's grace period
        $gracePeriods = [
            'Q1' => ['grace_end' => $year . '-04-20'],
            'Q2' => ['grace_end' => $year . '-07-20'],
            'Q3' => ['grace_end' => $year . '-10-20'],
            'Q4' => ['grace_end' => ($year + 1) . '-01-20'],
        ];
        
        foreach ($gracePeriods as $quarter => $dates) {
            $graceEnd = Carbon::parse($dates['grace_end']);
            if ($now->lte($graceEnd)) {
                return $quarter;
            }
        }
        
        // Fallback to simple month detection
        $month = $now->month;
        if ($month >= 1 && $month <= 3) {
            return 'Q1';
        } elseif ($month >= 4 && $month <= 6) {
            return 'Q2';
        } elseif ($month >= 7 && $month <= 9) {
            return 'Q3';
        } else {
            return 'Q4';
        }
    }
    
    /**
     * Get current quarter (simple month-based, not grace period aware)
     */
    public static function getCurrentQuarter()
    {
        $month = Carbon::now()->month;
        
        if ($month >= 1 && $month <= 3) {
            return 'Q1';
        } elseif ($month >= 4 && $month <= 6) {
            return 'Q2';
        } elseif ($month >= 7 && $month <= 9) {
            return 'Q3';
        } else {
            return 'Q4';
        }
    }
    
    /**
     * Get due date for a quarter (grace period end date)
     */
    public static function getDueDateForQuarter($quarter, $year)
    {
        switch ($quarter) {
            case 'Q1':
                return Carbon::create($year, 4, 20);
            case 'Q2':
                return Carbon::create($year, 7, 20);
            case 'Q3':
                return Carbon::create($year, 10, 20);
            case 'Q4':
                return Carbon::create($year + 1, 1, 20);
        }
        
        return null;
    }
    
    /**
     * Get quarter end date (actual quarter end, not including grace)
     */
    public static function getQuarterEndDate($quarter, $year)
    {
        switch ($quarter) {
            case 'Q1':
                return Carbon::create($year, 3, 31);
            case 'Q2':
                return Carbon::create($year, 6, 30);
            case 'Q3':
                return Carbon::create($year, 9, 30);
            case 'Q4':
                return Carbon::create($year, 12, 31);
        }
        
        return null;
    }
    
    /**
     * Check if a quarter is currently in grace period
     */
    public static function isInGracePeriod($quarter, $year = null)
    {
        $year = $year ?: date('Y');
        $now = Carbon::now();
        $quarterEnd = self::getQuarterEndDate($quarter, $year);
        $dueDate = self::getDueDateForQuarter($quarter, $year);
        
        if (!$quarterEnd || !$dueDate) {
            return false;
        }
        
        return $now->gt($quarterEnd) && $now->lte($dueDate);
    }
    
    /**
     * Check if a quarter is still open for submission (including grace period)
     */
    public static function isQuarterOpen($quarter, $year = null)
    {
        $year = $year ?: date('Y');
        $now = Carbon::now();
        $dueDate = self::getDueDateForQuarter($quarter, $year);
        
        if (!$dueDate) {
            return false;
        }
        
        return $now->lte($dueDate);
    }
    
    /**
     * Check if employee can submit for current quarter
     */
    public static function canSubmitForCurrentQuarter($employeeNumber)
    {
        $quarterStatus = self::getQuartersWithStatus($employeeNumber);
        return $quarterStatus['can_submit_current'];
    }
    
    /**
     * Check if employee has submitted for a specific quarter
     */
    public static function hasSubmittedForQuarter($employeeNumber, $quarter, $year = null)
    {
        $year = $year ?: date('Y');
        
        return Appraisal::where('employee_number', $employeeNumber)
            ->where('period', $quarter)
            ->whereYear('start_date', $year)
            ->whereIn('status', ['submitted', 'in_review', 'completed', 'approved'])
            ->exists();
    }
    
    /**
     * Get next available quarter for submission
     */
    public static function getNextAvailableQuarter($employeeNumber)
    {
        $quarterStatus = self::getQuartersWithStatus($employeeNumber);
        
        // Check each quarter and return the first one that can be submitted
        foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
            if (isset($quarterStatus['quarters'][$quarter])) {
                $quarterData = $quarterStatus['quarters'][$quarter];
                if ($quarterData['can_submit'] && !$quarterData['submission']) {
                    return $quarter;
                }
            }
        }
        
        // If no quarters can be submitted, return current quarter as fallback
        return $quarterStatus['current_quarter'];
    }
    
    /**
     * Check if a submission would be late for a quarter
     */
    public static function wouldBeLate($quarter, $year = null)
    {
        $year = $year ?: date('Y');
        $now = Carbon::now();
        $quarterEnd = self::getQuarterEndDate($quarter, $year);
        
        return $quarterEnd && $now->gt($quarterEnd);
    }
    
    /**
     * Get available quarters for submission
     */
    public static function getAvailableQuarters($employeeNumber)
    {
        $quarterStatus = self::getQuartersWithStatus($employeeNumber);
        $availableQuarters = [];
        
        // Check each quarter
        foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
            if (isset($quarterStatus['quarters'][$quarter])) {
                $quarterData = $quarterStatus['quarters'][$quarter];
                // Quarter is available if it can be submitted AND no submission exists
                if ($quarterData['can_submit'] && !$quarterData['submission']) {
                    $availableQuarters[] = $quarter;
                }
            }
        }
        
        return $availableQuarters;
    }
    
    /**
     * Check if all quarters are completed for the year
     */
    public static function areAllQuartersCompleted($employeeNumber, $year = null)
    {
        $quarterStatus = self::getQuartersWithStatus($employeeNumber, $year);
        
        foreach ($quarterStatus['quarters'] as $quarterData) {
            if ($quarterData['status'] !== 'completed') {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get days remaining in grace period for a quarter
     */
    public static function getDaysRemainingInGrace($quarter, $year = null)
    {
        $year = $year ?: date('Y');
        $now = Carbon::now();
        $dueDate = self::getDueDateForQuarter($quarter, $year);
        
        if (!$dueDate || $now->gt($dueDate)) {
            return 0;
        }
        
        if (!self::isInGracePeriod($quarter, $year)) {
            return 0;
        }
        
        return $now->diffInDays($dueDate, false);
    }
}