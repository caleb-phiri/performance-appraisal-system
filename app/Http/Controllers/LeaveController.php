<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
class LeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $leaves = Leave::where('employee_number', $user->employee_number)
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('leave.index', compact('leaves'));
    }

    public function create()
    {
        return view('leave.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'leave_type' => 'required|in:annual,sick,maternity,paternity,unpaid,other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10|max:500',
            'contact_address' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);
        
        // Calculate total days (including both start and end dates)
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;
        
        // Check if dates are valid
        if ($totalDays < 1) {
            return back()->withErrors([
                'end_date' => 'End date must be after start date.'
            ])->withInput();
        }
        
        // Check for overlapping leave (optional - can be commented out if not needed)
        $overlapping = Leave::where('employee_number', $user->employee_number)
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();
            
        if ($overlapping) {
            return back()->withErrors([
                'start_date' => 'You already have a leave request during this period.'
            ])->withInput();
        }
        
        $leave = Leave::create([
            'employee_number' => $user->employee_number,
            'employee_name' => $user->name,
            'job_title' => $user->job_title ?? 'Not specified',
            'department' => $user->department ?? 'Not specified',
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
            'contact_address' => $validated['contact_address'],
            'contact_phone' => $validated['contact_phone'],
            'status' => 'pending',
        ]);
        
        return redirect()->route('leave.index')
            ->with('success', 'Leave application submitted successfully!')
            ->with('info', 'Your leave request is pending approval.');
    }

   public function show(Leave $leave)
{
    $user = Auth::user();
    
    // Check if user can view this leave
    // Allow if: user owns the leave OR user is supervisor of the employee
    if ($leave->employee_number !== $user->employee_number) {
        // Check if user is a supervisor of this employee
        $isSupervisor = User::where('employee_number', $leave->employee_number)
            ->where('manager_id', $user->employee_number)
            ->exists();
            
        if (!$isSupervisor && $user->user_type !== 'supervisor') {
            abort(403, 'Unauthorized action.');
        }
    }
    
    return view('leave.show', compact('leave'));
}

    public function edit(Leave $leave)
    {
        // Check if user can edit this leave
        if ($leave->employee_number !== Auth::user()->employee_number) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only pending leaves can be edited
        if (!$leave->isPending()) {
            return redirect()->route('leave.index')
                ->with('error', 'Only pending leave requests can be edited.');
        }
        
        return view('leave.edit', compact('leave'));
    }

    public function update(Request $request, Leave $leave)
    {
        // Check if user can update this leave
        if ($leave->employee_number !== Auth::user()->employee_number) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only pending leaves can be updated
        if (!$leave->isPending()) {
            return redirect()->route('leave.index')
                ->with('error', 'Only pending leave requests can be updated.');
        }
        
        $validated = $request->validate([
            'leave_type' => 'required|in:annual,sick,maternity,paternity,unpaid,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10|max:500',
            'contact_address' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);
        
        // Recalculate total days
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;
        
        $validated['total_days'] = $totalDays;
        
        $leave->update($validated);
        
        return redirect()->route('leave.index')
            ->with('success', 'Leave application updated successfully!');
    }

    public function destroy(Leave $leave)
    {
        // Check if user can delete this leave
        if ($leave->employee_number !== Auth::user()->employee_number) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only pending leaves can be cancelled
        if (!$leave->isPending()) {
            return redirect()->route('leave.index')
                ->with('error', 'Only pending leave requests can be cancelled.');
        }
        
        $leave->update(['status' => 'cancelled']);
        
        return redirect()->route('leave.index')
            ->with('success', 'Leave application cancelled successfully!');
    }
}