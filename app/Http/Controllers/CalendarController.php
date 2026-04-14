<?php
// app/Http/Controllers/CalendarController.php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $currentDate = Carbon::createFromDate($year, $month, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
        
        // Get user's leaves for the current month view (including overlapping into next/prev months)
        $leaves = Leave::with('user')
            ->where('employee_number', $user->employee_number) // Filter by current user
            ->where(function($query) use ($year, $month) {
                $query->whereMonth('start_date', $month)
                      ->whereYear('start_date', $year)
                      ->orWhereMonth('end_date', $month)
                      ->whereYear('end_date', $year)
                      ->orWhere(function($q) use ($year, $month) {
                          $q->where('start_date', '<=', Carbon::createFromDate($year, $month, 1)->endOfMonth())
                            ->where('end_date', '>=', Carbon::createFromDate($year, $month, 1)->startOfMonth());
                      });
            })
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('start_date')
            ->get();
        
        // Get leave statistics for current user only
        $statistics = [
            'total' => Leave::where('employee_number', $user->employee_number)
                ->whereYear('created_at', $year)
                ->count(),
            'approved' => Leave::where('employee_number', $user->employee_number)
                ->whereYear('created_at', $year)
                ->where('status', 'approved')
                ->count(),
            'pending' => Leave::where('employee_number', $user->employee_number)
                ->whereYear('created_at', $year)
                ->where('status', 'pending')
                ->count(),
            'rejected' => Leave::where('employee_number', $user->employee_number)
                ->whereYear('created_at', $year)
                ->where('status', 'rejected')
                ->count(),
        ];
        
        // Get upcoming leaves for current user only
        $upcomingLeaves = Leave::with('user')
            ->where('employee_number', $user->employee_number)
            ->where('status', 'approved')
            ->where('start_date', '>=', Carbon::today())
            ->orderBy('start_date')
            ->limit(5)
            ->get();
        
        // Department wise leave distribution (simplified for current user)
        $departmentLeaves = collect(); // Empty collection since we're showing only user's leaves
        
        return view('calendar.index', compact(
            'currentDate', 
            'prevMonth', 
            'nextMonth', 
            'leaves', 
            'statistics',
            'upcomingLeaves',
            'departmentLeaves'
        ));
    }
    
    public function getEvents(Request $request)
    {
        $user = Auth::user();
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);
        
        $leaves = Leave::with('user')
            ->where('employee_number', $user->employee_number) // Filter by current user
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function($q) use ($start, $end) {
                          $q->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                      });
            })
            ->whereIn('status', ['approved', 'pending'])
            ->get();
        
        $events = [];
        
        foreach ($leaves as $leave) {
            $color = $this->getStatusColor($leave->status);
            
            $events[] = [
                'id' => $leave->id,
                'title' => $leave->leave_type_name, // Simpler title for personal view
                'start' => $leave->start_date->format('Y-m-d'),
                'end' => $leave->end_date->copy()->addDay()->format('Y-m-d'), // Add day for full-day display
                'color' => $color,
                'textColor' => '#ffffff',
                'url' => route('leave.show', $leave->id),
                'extendedProps' => [
                    'type' => $leave->leave_type,
                    'type_name' => $leave->leave_type_name,
                    'status' => $leave->status,
                    'days' => $leave->total_days,
                    'employee' => $leave->user->name,
                ]
            ];
        }
        
        return response()->json($events);
    }
    
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'approved':
                return '#10b981'; // Green
            case 'pending':
                return '#f59e0b'; // Orange
            case 'rejected':
                return '#ef4444'; // Red
            default:
                return '#6b7280'; // Gray
        }
    }
    
    public function export(Request $request)
    {
        $user = Auth::user();
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $leaves = Leave::with('user')
            ->where('employee_number', $user->employee_number) // Export only user's leaves
            ->whereMonth('start_date', $month)
            ->whereYear('start_date', $year)
            ->where('status', 'approved')
            ->get();
        
        // Generate CSV
        $filename = "my_leaves_{$month}_{$year}.csv";
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Add headers
        fputcsv($handle, ['Leave Type', 'Start Date', 'End Date', 'Days', 'Status']);
        
        // Add data (simplified for personal export)
        foreach ($leaves as $leave) {
            fputcsv($handle, [
                $leave->leave_type_name,
                $leave->start_date->format('Y-m-d'),
                $leave->end_date->format('Y-m-d'),
                $leave->total_days,
                $leave->status
            ]);
        }
        
        fclose($handle);
        exit;
    }
}