<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveBalanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $year = $request->get('year', Carbon::now()->year);
        
        // Define leave entitlements
        $leaveEntitlements = [
            'annual' => 21,
            'sick' => 14,
            'maternity' => 90,
            'paternity' => 10,
            'study' => 10,
            'unpaid' => 30,
            'emergency' => 5,
            'compassionate' => 5,
            'other' => 10,
        ];
        
        // Get all approved leaves for the user in the selected year
        $approvedLeaves = Leave::where('employee_number', $user->employee_number)
            ->where('status', 'approved')
            ->whereYear('start_date', $year)
            ->orWhere(function($query) use ($user, $year) {
                $query->where('employee_number', $user->employee_number)
                    ->where('status', 'approved')
                    ->whereYear('end_date', $year);
            })
            ->get();
        
        // Calculate taken days by leave type
        $takenDays = [];
        $totalTaken = 0;
        $monthlyBreakdown = [];
        $approvedCount = 0;
        
        foreach ($leaveEntitlements as $type => $entitled) {
            $takenDays[$type] = 0;
        }
        
        foreach ($approvedLeaves as $leave) {
            $type = $leave->leave_type;
            if (isset($takenDays[$type])) {
                $takenDays[$type] += $leave->total_days;
                $totalTaken += $leave->total_days;
                $approvedCount++;
                
                // Monthly breakdown
                $month = Carbon::parse($leave->start_date)->format('F');
                if (!isset($monthlyBreakdown[$month])) {
                    $monthlyBreakdown[$month] = 0;
                }
                $monthlyBreakdown[$month] += $leave->total_days;
            }
        }
        
        // Calculate remaining days
        $remainingDays = [];
        $totalRemaining = 0;
        
        foreach ($leaveEntitlements as $type => $entitled) {
            $taken = $takenDays[$type] ?? 0;
            $remaining = $entitled - $taken;
            $remainingDays[$type] = max(0, $remaining);
            $totalRemaining += $remaining;
        }
        
        // Calculate totals
        $totalEntitled = array_sum($leaveEntitlements);
        
        // Get leave history by month for chart - FIXED FOR SQLITE
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $leaveHistory = [];
        
        // Get all approved leaves for the year
        $yearLeaves = Leave::where('employee_number', $user->employee_number)
            ->where('status', 'approved')
            ->where(function($query) use ($year) {
                $query->whereYear('start_date', $year)
                      ->orWhereYear('end_date', $year);
            })
            ->get();
        
        // Initialize leave history array
        foreach ($months as $month) {
            $leaveHistory[$month] = 0;
        }
        
        // Calculate days per month
        foreach ($yearLeaves as $leave) {
            $startMonth = Carbon::parse($leave->start_date)->format('M');
            $endMonth = Carbon::parse($leave->end_date)->format('M');
            
            if ($startMonth == $endMonth) {
                // Leave within same month
                $leaveHistory[$startMonth] += $leave->total_days;
            } else {
                // Leave spans multiple months - simplified calculation
                // You might want to implement more accurate day distribution
                $leaveHistory[$startMonth] += floor($leave->total_days / 2);
                $leaveHistory[$endMonth] += ceil($leave->total_days / 2);
            }
        }
        
        // Get pending leaves
        $pendingLeaves = Leave::where('employee_number', $user->employee_number)
            ->where('status', 'pending')
            ->whereYear('created_at', $year)
            ->count();
        
        // Get upcoming leaves
        $upcomingLeaves = Leave::where('employee_number', $user->employee_number)
            ->where('status', 'approved')
            ->where('start_date', '>=', Carbon::today())
            ->orderBy('start_date')
            ->limit(5)
            ->get();
        
        // Get available years for filter - FIXED FOR SQLITE
        $availableYears = Leave::where('employee_number', $user->employee_number)
            ->select(DB::raw('strftime("%Y", created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        if (empty($availableYears)) {
            $availableYears = [$year];
        }
        
        return view('leave.balance', compact(
            'user',
            'year',
            'leaveEntitlements',
            'takenDays',
            'remainingDays',
            'totalEntitled',
            'totalTaken',
            'totalRemaining',
            'monthlyBreakdown',
            'leaveHistory',
            'pendingLeaves',
            'upcomingLeaves',
            'availableYears',
            'months',
            'approvedCount'
        ));
    }
    
    public function history(Request $request)
    {
        $user = Auth::user();
        $year = $request->get('year', Carbon::now()->year);
        
        $leaveHistory = Leave::where('employee_number', $user->employee_number)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('leave.history', compact('leaveHistory', 'year'));
    }
}