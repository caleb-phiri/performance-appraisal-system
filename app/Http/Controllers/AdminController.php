<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appraisal;
use App\Models\EmployeeRatingSupervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema; // ADD THIS LINE
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ============================================
    // ADMIN DASHBOARD & MAIN PAGES
    // ============================================

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $stats = $this->getDashboardStats($user);
        
        // Get all supervisors
        $supervisors = User::where('user_type', 'supervisor')
            ->orderBy('name')
            ->get()
            ->map(function ($supervisor) {
                // Count appraisals for each supervisor's team using new system
                $employeeNumbers = DB::table('employee_rating_supervisors')
                    ->where('supervisor_id', $supervisor->employee_number)
                    ->pluck('employee_number');
                
                $supervisor->appraisals_count = Appraisal::whereIn('employee_number', $employeeNumbers)
                    ->whereIn('status', ['submitted', 'approved'])->count();
                
                $supervisor->team_size = $employeeNumbers->count();
                return $supervisor;
            });

        // Get recent appraisals for this supervisor's team
        $employeeNumbers = DB::table('employee_rating_supervisors')
            ->where('supervisor_id', $user->employee_number)
            ->pluck('employee_number');
        
        $recentAppraisals = Appraisal::with(['user'])
            ->whereIn('employee_number', $employeeNumbers)
            ->whereIn('status', ['submitted', 'approved', 'in_review'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($appraisal) use ($user) {
                // Get all supervisors for this employee - FIXED ambiguous column
                $supervisors = DB::table('employee_rating_supervisors as ers')
                    ->where('ers.employee_number', $appraisal->employee_number)
                    ->join('users', 'ers.supervisor_id', '=', 'users.employee_number')
                    ->select('users.name', 'users.employee_number as supervisor_number', 'ers.is_primary')
                    ->get();
                
                $appraisal->supervisors = $supervisors;
                $appraisal->current_user_is_primary = $supervisors->contains(function ($supervisor) use ($user) {
                    return $supervisor->supervisor_number === $user->employee_number && $supervisor->is_primary;
                });
                
                return $appraisal;
            });

        return view('admin.dashboard', compact('user', 'stats', 'supervisors', 'recentAppraisals'));
    }

    // ============================================
    // USER MANAGEMENT
    // ============================================

    /**
     * View all users (for admin)
     */
    public function users(Request $request)
    {
        $query = User::query();
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_number', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('job_title', 'LIKE', "%{$search}%")
                  ->orWhere('department', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply user type filter
        if ($request->has('user_type') && !empty($request->user_type)) {
            $query->where('user_type', $request->user_type);
        }
        
        // Apply status filter
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where(function($q) {
                    $q->where('is_active', false)
                      ->orWhereNull('is_active')
                      ->orWhere('onboarded', false);
                });
            }
        }
        
        $users = $query->orderBy('name')->paginate(20);
        
        // Get stats for the view
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'left_company_users' => User::where('is_active', false)
                ->orWhere('status', 'inactive')
                ->orWhereNull('is_active')
                ->orWhere('employment_status', 'inactive')
                ->orWhere('onboarded', false)
                ->count(),
            'supervisors' => User::where('user_type', 'supervisor')->count(),
            'employees' => User::where('user_type', 'employee')->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * View inactive users
     */
    public function inactiveUsers(Request $request)
    {
        // Get inactive users based on various possible conditions
        $users = User::where(function($query) {
            // Check for various inactive conditions
            $query->where('is_active', false)
                  ->orWhere('status', 'inactive')
                  ->orWhereNull('is_active')
                  ->orWhere('employment_status', 'inactive')
                  ->orWhere('onboarded', false);
        })->orderBy('created_at', 'desc')->paginate(20);
        
        // Get stats for the view
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'left_company_users' => $users->total(),
            'supervisors' => User::where('user_type', 'supervisor')->count(),
        ];
        
        return view('admin.users.index', [
            'users' => $users,
            'title' => 'Inactive Users',
            'showInactiveOnly' => true,
            'stats' => $stats
        ]);
    }

    /**
     * View specific user
     */
    public function showUser($employeeNumber)
    {
        $user = User::where('employee_number', $employeeNumber)->firstOrFail();
        
        // Get rating supervisors (multiple)
        $ratingSupervisors = $user->ratingSupervisors ?? collect();
        $hasMultipleSupervisors = $ratingSupervisors->count() > 1;
        
        // Get team members if user is a supervisor
        $teamMembers = [];
        $appraisalStats = [];
        $approvalStats = [];
        
        if ($user->isSupervisor()) {
            $teamMembers = User::where('manager_id', $user->employee_number)
                ->orderBy('name')
                ->get();
            
            // Get appraisal statistics for supervisor's team
            $appraisalStats = [
                'total_appraisals' => Appraisal::whereHas('user', function($query) use ($user) {
                    $query->where('manager_id', $user->employee_number);
                })->count(),
                'approved_appraisals' => Appraisal::whereHas('user', function($query) use ($user) {
                    $query->where('manager_id', $user->employee_number);
                })->where('status', 'approved')->count(),
                'pending_appraisals' => Appraisal::whereHas('user', function($query) use ($user) {
                    $query->where('manager_id', $user->employee_number);
                })->where('status', 'submitted')->count(),
                'draft_appraisals' => Appraisal::whereHas('user', function($query) use ($user) {
                    $query->where('manager_id', $user->employee_number);
                })->where('status', 'draft')->count(),
            ];
            
            // Add approval stats
            $approvalStats = [
                'total' => $appraisalStats['total_appraisals'],
                'approved' => $appraisalStats['approved_appraisals'],
                'pending' => $appraisalStats['pending_appraisals'],
                'rejected' => Appraisal::whereHas('user', function($query) use ($user) {
                    $query->where('manager_id', $user->employee_number);
                })->where('status', 'rejected')->count(),
            ];
        }
        
        // Get user's appraisals
        $userAppraisals = Appraisal::where('employee_number', $employeeNumber)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.users.show', compact('user', 'teamMembers', 'appraisalStats', 'userAppraisals', 'approvalStats', 'ratingSupervisors', 'hasMultipleSupervisors'));
    }

    /**
     * Reset user password form
     */
    public function resetPasswordForm($employeeNumber)
    {
        $user = User::where('employee_number', $employeeNumber)->firstOrFail();
        return view('admin.users.reset-password', compact('user'));
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $employeeNumber)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('employee_number', $employeeNumber)->firstOrFail();
        
        $user->update([
            'password' => Hash::make($request->password),
            'password_setup_skipped' => false,
        ]);

        return redirect()->route('admin.users.show', $user->employee_number)
                         ->with('success', 'Password has been reset successfully.');
    }

    /**
     * Remove password (allow login with employee number only)
     */
    public function removePassword($employeeNumber)
    {
        $user = User::where('employee_number', $employeeNumber)->firstOrFail();
        
        $user->update([
            'password' => null,
            'password_setup_skipped' => true,
        ]);

        return redirect()->route('admin.users.show', $user->employee_number)
                         ->with('success', 'Password has been removed. User can now login with employee number only.');
    }

  /**
 * Reactivate user
 */
public function reactivate($employeeNumber)
{
    try {
        DB::beginTransaction();
        
        $user = User::where('employee_number', $employeeNumber)->firstOrFail();
        
        // Log the reactivation attempt
        Log::info('Reactivating user', [
            'employee_number' => $employeeNumber,
            'name' => $user->name,
            'current_status' => [
                'is_active' => $user->is_active,
                'employment_status' => $user->employment_status,
                'left_company' => $user->left_company,
                'left_at' => $user->left_at,
                'left_reason' => $user->left_reason,
            ]
        ]);
        
        // Check what columns actually exist in the database
        $existingColumns = Schema::getColumnListing('users');
        Log::info('Existing columns in users table:', $existingColumns);
        
        // Prepare update data - only update columns that exist
        $updateData = [
            'is_active' => true,
            'deactivated_at' => null,
            'left_reason' => null,
            'left_notes' => null,
            'employment_status' => 'active',
            'left_company' => false,
            'left_at' => null,
            'updated_at' => now(),
        ];
        
        // Only add status column if it exists
        if (in_array('status', $existingColumns)) {
            $updateData['status'] = 'active';
        }
        
        // Only add onboarded column if it exists
        if (in_array('onboarded', $existingColumns)) {
            $updateData['onboarded'] = true;
        }
        
        // Log the update data
        Log::info('Update data for reactivation:', $updateData);
        
        // Update the user
        $updated = $user->update($updateData);
        
        if (!$updated) {
            Log::error('Failed to update user status', [
                'employee_number' => $employeeNumber,
                'update_data' => $updateData
            ]);
            
            DB::rollBack();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to reactivate user. Please try again.'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to reactivate user. Please try again.');
        }
        
        // Log successful reactivation
        Log::info('User reactivated successfully', [
            'employee_number' => $employeeNumber,
            'name' => $user->name,
            'updated_data' => $updateData
        ]);
        
        DB::commit();
        
        // Check if request expects JSON (AJAX request)
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $user->name . ' has been reactivated successfully!',
                'user' => [
                    'employee_number' => $user->employee_number,
                    'name' => $user->name,
                    'is_active' => true
                ]
            ]);
        }
        
        // Regular form submission - redirect with flash message
        return redirect()->back()
            ->with('success', $user->name . ' has been reactivated successfully!');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Error reactivating user: ' . $e->getMessage(), [
            'employee_number' => $employeeNumber,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Error reactivating user: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'Error reactivating user: ' . $e->getMessage());
    }
}
    /**
     * Toggle user status
     */
    public function toggleStatus($employeeNumber)
    {
        $user = User::where('employee_number', $employeeNumber)->firstOrFail();
        
        $newStatus = !$user->is_active;
        
        $user->update([
            'is_active' => $newStatus,
            'deactivated_at' => $newStatus ? null : now(),
            'left_reason' => $newStatus ? null : $user->left_reason,
            'left_notes' => $newStatus ? null : $user->left_notes,
            'employment_status' => $newStatus ? 'active' : 'inactive',
        ]);

        $statusMessage = $newStatus ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.users.show', $user->employee_number)
                         ->with('success', "User has been {$statusMessage} successfully.");
    }

    /**
     * Mark user as left company
     */
    public function markAsLeft(Request $request, $employeeNumber)
    {
        $user = User::where('employee_number', $employeeNumber)->firstOrFail();
        
        $request->validate([
            'left_reason' => 'required|string|max:255',
            'left_notes' => 'nullable|string|max:1000',
        ]);
        
        $user->update([
            'is_active' => false,
            'deactivated_at' => now(),
            'left_company' => true,
            'left_at' => now(),
            'left_reason' => $request->left_reason,
            'left_notes' => $request->left_notes,
            'employment_status' => 'inactive',
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User has been marked as left company successfully.');
    }

    // ============================================
    // SUPERVISOR ASSIGNMENT MANAGEMENT
    // ============================================

    /**
     * Show supervisor assignments page
     */
    public function supervisorAssignments()
    {
        // Get all active supervisors
        $availableSupervisors = User::where('user_type', 'supervisor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Get all active employees for display
        $employees = User::where('user_type', 'employee')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('admin.supervisor-assignments', compact('availableSupervisors', 'employees'));
    }
    
    /**
     * Get employee's current supervisors
     */
    public function getEmployeeSupervisors($employeeNumber)
    {
        try {
            $employee = User::where('employee_number', $employeeNumber)->firstOrFail();
            
            // Get supervisors from pivot table
            $supervisors = DB::table('employee_rating_supervisors as ers')
                ->where('ers.employee_number', $employeeNumber)
                ->whereNull('ers.kpa_id') // Only relationship records
                ->join('users', 'ers.supervisor_id', '=', 'users.employee_number')
                ->select(
                    'users.employee_number as supervisor_id',
                    'users.name',
                    'users.job_title',
                    'users.department',
                    'ers.rating_weight',
                    'ers.is_primary',
                    'ers.relationship_type',
                    'ers.can_view_appraisal',
                    'ers.can_approve_appraisal',
                    'ers.notes'
                )
                ->get();
            
            return response()->json([
                'success' => true,
                'supervisors' => $supervisors,
                'employee' => [
                    'employee_number' => $employee->employee_number,
                    'name' => $employee->name,
                    'current_manager' => $employee->manager ? $employee->manager->name : null,
                    'current_manager_id' => $employee->manager_id,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting employee supervisors: ' . $e->getMessage(), [
                'employee_number' => $employeeNumber,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Employee not found: ' . $e->getMessage()
            ], 404);
        }
    }
    
    /**
     * Update employee's supervisors
     */
    public function updateEmployeeSupervisors(Request $request, $employeeNumber)
    {
        try {
            $employee = User::where('employee_number', $employeeNumber)->firstOrFail();
            
            $request->validate([
                'supervisors' => 'required|array',
                'supervisors.*.supervisor_id' => 'required|exists:users,employee_number',
                'supervisors.*.rating_weight' => 'required|integer|min:1|max:100',
                'supervisors.*.is_primary' => 'boolean',
                'supervisors.*.can_approve_appraisal' => 'boolean',
                'supervisors.*.relationship_type' => 'required|in:direct,matrix,functional,other',
                'supervisors.*.notes' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            // Clear existing assignments
            DB::table('employee_rating_supervisors')
                ->where('employee_number', $employeeNumber)
                ->whereNull('kpa_id')
                ->delete();

            // Add new assignments
            $primarySupervisorSet = false;
            foreach ($request->input('supervisors', []) as $supervisorData) {
                $isPrimary = $supervisorData['is_primary'] ?? false;
                
                // Ensure only one primary supervisor
                if ($isPrimary && $primarySupervisorSet) {
                    throw new \Exception('Only one supervisor can be marked as primary');
                }
                
                if ($isPrimary) {
                    $primarySupervisorSet = true;
                }
                
                DB::table('employee_rating_supervisors')->insert([
                    'employee_number' => $employeeNumber,
                    'supervisor_id' => $supervisorData['supervisor_id'],
                    'relationship_type' => $supervisorData['relationship_type'],
                    'rating_weight' => $supervisorData['rating_weight'],
                    'is_primary' => $isPrimary,
                    'can_view_appraisal' => true,
                    'can_approve_appraisal' => $supervisorData['can_approve_appraisal'] ?? $isPrimary,
                    'notes' => $supervisorData['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Update employee's manager_id for backward compatibility
            $primarySupervisor = collect($request->input('supervisors', []))
                ->firstWhere('is_primary', true);
            
            if ($primarySupervisor) {
                $employee->update([
                    'manager_id' => $primarySupervisor['supervisor_id']
                ]);
            } else {
                // If no primary, set to null
                $employee->update([
                    'manager_id' => null
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Supervisor assignments updated successfully',
                'has_multiple_supervisors' => count($request->input('supervisors', [])) > 1
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating employee supervisors: ' . $e->getMessage(), [
                'employee_number' => $employeeNumber,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Search users for assignment
     */
    public function searchUsers(Request $request)
    {
        try {
            Log::info('Search users called', ['query' => $request->input('q'), 'type' => $request->input('type')]);
            
            $query = $request->input('q', '');
            $type = $request->input('type', 'employee');
            
            $users = User::query();
            
            if ($type === 'employee') {
                $users->where('user_type', 'employee');
            } elseif ($type === 'supervisor') {
                $users->where('user_type', 'supervisor');
            }
            
            // Apply active status filter
            $users->where('is_active', true);
            
            // Apply search query
            if (!empty($query)) {
                $users->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('employee_number', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('job_title', 'like', "%{$query}%");
                });
            }
            
            $results = $users->orderBy('name')
                ->limit(10)
                ->get(['employee_number', 'name', 'job_title', 'department', 'user_type', 'email']);
            
            Log::info('Search results found', ['count' => $results->count()]);
            
            return response()->json([
                'success' => true,
                'users' => $results,
                'count' => $results->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in searchUsers', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error searching users: ' . $e->getMessage(),
                'users' => []
            ], 500);
        }
    }

    // ============================================
    // TEAM MANAGEMENT
    // ============================================

    /**
     * Supervisor: View team members
     */
    public function teamMembers()
    {
        $user = Auth::user();
        
        if (!$user->isSupervisor()) {
            abort(403, 'Only supervisors can access this page.');
        }

        // Get team members using the new multiple supervisors system
        $teamMembers = User::whereHas('ratingSupervisors', function($query) use ($user) {
            $query->where('supervisor_id', $user->employee_number);
        })
        ->where('user_type', 'employee')
        ->where('is_active', true)
        ->orderBy('name')
        ->paginate(15);
        
        // Get approval stats - using the correct method name
        $approvalStats = $this->getApprovalStats($user);
        
        // Get appraisal statistics
        $appraisalStats = [
            'total_appraisals' => $approvalStats['total'],
            'approved_appraisals' => $approvalStats['approved'],
            'pending_appraisals' => $approvalStats['pending'],
            'approval_rate' => $approvalStats['approval_rate']
        ];

        return view('admin.team-members', compact('teamMembers', 'appraisalStats', 'approvalStats', 'user'));
    }

    // ============================================
    // APPRAISAL MANAGEMENT
    // ============================================

    /**
     * Admin: View all appraisals with filtering
     */
    public function allAppraisals(Request $request)
    {
        $query = Appraisal::with(['user']);
        
        // Apply status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Apply period filter
        if ($request->has('period') && $request->period != '') {
            $query->where('period', $request->period);
        }
        
        // Apply search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('form_type', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('employee_number', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Get paginated results
        $appraisals = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Manually add supervisor for each appraisal
        $appraisals->getCollection()->transform(function ($appraisal) {
            if ($appraisal->user && $appraisal->user->manager_id) {
                $appraisal->supervisor = User::where('employee_number', $appraisal->user->manager_id)->first();
            }
            return $appraisal;
        });
        
        // Stats for dashboard
        $stats = [
            'total' => Appraisal::count(),
            'approved' => Appraisal::where('status', 'approved')->count(),
            'submitted' => Appraisal::where('status', 'submitted')->count(),
            'draft' => Appraisal::where('status', 'draft')->count(),
            'rejected' => Appraisal::where('status', 'rejected')->count(),
        ];

        return view('admin.appraisals.index', compact('appraisals', 'stats'));
    }

    /**
     * Admin: View supervisor-specific appraisals
     */
    public function supervisorAppraisals($supervisorId)
    {
        $supervisor = User::findOrFail($supervisorId);
        
        // Get approved appraisals for supervisor's team
        $approvedAppraisals = Appraisal::with(['user'])
            ->whereHas('user', function($query) use ($supervisor) {
                $query->where('manager_id', $supervisor->employee_number);
            })
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'approved_page');

        // Get submitted appraisals for supervisor's team
        $submittedAppraisals = Appraisal::with(['user'])
            ->whereHas('user', function($query) use ($supervisor) {
                $query->where('manager_id', $supervisor->employee_number);
            })
            ->where('status', 'submitted')
            ->orderBy('updated_at', 'desc')
            ->paginate(10, ['*'], 'submitted_page');

        // Get team members
        $teamMembers = User::where('manager_id', $supervisor->employee_number)
            ->where('user_type', 'employee')
            ->get();

        return view('admin.supervisor-appraisals', compact(
            'supervisor', 
            'approvedAppraisals', 
            'submittedAppraisals',
            'teamMembers'
        ));
    }

    // ============================================
    // REPORTS & EXPORTS
    // ============================================

    /**
     * Admin: Appraisal report
     */
    public function appraisalReport()
    {
        // Get overall statistics
        $totalAppraisals = Appraisal::count();
        $approvedCount = Appraisal::where('status', 'approved')->count();
        $submittedCount = Appraisal::where('status', 'submitted')->count();
        $draftCount = Appraisal::where('status', 'draft')->count();
        
        // Get supervisor-wise statistics
        $supervisors = User::where('user_type', 'supervisor')
            ->get()
            ->map(function ($supervisor) {
                $supervisor->approved_appraisals_count = Appraisal::whereHas('user', function($query) use ($supervisor) {
                    $query->where('manager_id', $supervisor->employee_number);
                })->where('status', 'approved')->count();
                
                $supervisor->submitted_appraisals_count = Appraisal::whereHas('user', function($query) use ($supervisor) {
                    $query->where('manager_id', $supervisor->employee_number);
                })->where('status', 'submitted')->count();
                
                $supervisor->total_appraisals_count = Appraisal::whereHas('user', function($query) use ($supervisor) {
                    $query->where('manager_id', $supervisor->employee_number);
                })->whereIn('status', ['submitted', 'approved', 'draft'])->count();
                
                $supervisor->team_size = User::where('manager_id', $supervisor->employee_number)->count();
                
                return $supervisor;
            });

        // Get monthly trends
        $monthlyData = $this->getMonthlyTrends();

        // Get department-wise statistics
        $departmentStats = $this->getDepartmentStats();

        return view('admin.appraisal-report', compact(
            'totalAppraisals',
            'approvedCount',
            'submittedCount',
            'draftCount',
            'supervisors',
            'monthlyData',
            'departmentStats'
        ));
    }

    /**
     * Admin: Export appraisals
     */
    public function exportAppraisals()
    {
        $appraisals = Appraisal::with(['user'])->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="appraisals_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($appraisals) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Appraisal ID',
                'Employee Name',
                'Employee Number',
                'Supervisor Name',
                'Supervisor ID',
                'Period',
                'Status',
                'Self Score',
                'Supervisor Score',
                'Overall Score',
                'Created Date',
                'Submitted Date',
                'Approved Date'
            ]);

            // Add data rows
            foreach ($appraisals as $appraisal) {
                // Get supervisor name if exists
                $supervisorName = 'N/A';
                $supervisorId = 'N/A';
                if ($appraisal->user && $appraisal->user->manager_id) {
                    $supervisor = User::where('employee_number', $appraisal->user->manager_id)->first();
                    $supervisorName = $supervisor ? $supervisor->name : 'N/A';
                    $supervisorId = $appraisal->user->manager_id;
                }
                
                fputcsv($file, [
                    $appraisal->id,
                    $appraisal->user->name ?? 'N/A',
                    $appraisal->employee_number,
                    $supervisorName,
                    $supervisorId,
                    $appraisal->period,
                    $appraisal->status,
                    $appraisal->self_score ?? 0,
                    $appraisal->supervisor_score ?? 0,
                    $appraisal->overall_score ?? 0,
                    $appraisal->created_at->format('Y-m-d'),
                    $appraisal->submitted_at ? $appraisal->submitted_at->format('Y-m-d') : 'N/A',
                    $appraisal->approved_at ? $appraisal->approved_at->format('Y-m-d') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export users to CSV
     */
    public function exportUsers()
    {
        $users = User::all();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Employee Number',
                'Name',
                'Job Title',
                'User Type',
                'Department',
                'Workstation Type',
                'Toll Plaza',
                'HQ Department',
                'Manager ID',
                'Manager Name',
                'Email',
                'Has Password',
                'Onboarded',
                'Active',
                'Created At'
            ]);

            // Add data rows
            foreach ($users as $user) {
                // Get manager name
                $managerName = 'N/A';
                if ($user->manager_id) {
                    $manager = User::where('employee_number', $user->manager_id)->first();
                    $managerName = $manager ? $manager->name : 'N/A';
                }
                
                fputcsv($file, [
                    $user->employee_number,
                    $user->name,
                    $user->job_title,
                    $user->user_type,
                    $user->department,
                    $user->workstation_type,
                    $user->toll_plaza ?? 'N/A',
                    $user->hq_department ?? 'N/A',
                    $user->manager_id ?? 'N/A',
                    $managerName,
                    $user->email ?? 'N/A',
                    $user->hasPassword() ? 'Yes' : 'No',
                    $user->is_onboarded ? 'Yes' : 'No',
                    $user->is_active ? 'Yes' : 'No',
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats($user)
    {
        return [
            'total_users' => User::count(),
            'supervisors' => User::where('user_type', 'supervisor')->count(),
            'employees' => User::where('user_type', 'employee')->count(),
            'users_with_password' => User::whereNotNull('password')->count(),
            'users_without_password' => User::whereNull('password')->count(),
            'total_appraisals' => Appraisal::count(),
            'pending_approvals' => Appraisal::where('status', 'submitted')->count(),
            'approved_appraisals' => Appraisal::where('status', 'approved')->count(),
            'active_users' => User::where('last_login_at', '>=', Carbon::now()->subDays(30))->count(),
            'monthly_appraisals' => Appraisal::whereMonth('created_at', Carbon::now()->month)->count(),
            'completion_rate' => $this->calculateCompletionRate(),
            'users_with_multiple_supervisors' => $this->countUsersWithMultipleSupervisors(),
        ];
    }

    /**
     * Calculate appraisal completion rate
     */
    private function calculateCompletionRate()
    {
        $totalAssigned = User::where('user_type', 'employee')->count();
        $completed = Appraisal::whereIn('status', ['approved', 'completed'])->count();
        
        if ($totalAssigned > 0) {
            return round(($completed / $totalAssigned) * 100, 2);
        }
        
        return 0;
    }

    /**
     * Count users with multiple supervisors
     */
    private function countUsersWithMultipleSupervisors()
    {
        $count = 0;
        $employees = User::where('user_type', 'employee')->get();
        
        foreach ($employees as $employee) {
            if ($employee->ratingSupervisors->count() > 1) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Get monthly trends data
     */
    private function getMonthlyTrends()
    {
        $months = [];
        $approvedData = [];
        $submittedData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');
            
            $approved = Appraisal::where('status', 'approved')
                ->whereMonth('approved_at', $date->month)
                ->whereYear('approved_at', $date->year)
                ->count();
                
            $submitted = Appraisal::where('status', 'submitted')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
                
            $months[] = $month;
            $approvedData[] = $approved;
            $submittedData[] = $submitted;
        }
        
        return [
            'months' => $months,
            'approved' => $approvedData,
            'submitted' => $submittedData
        ];
    }

    /**
     * Get department statistics
     */
    private function getDepartmentStats()
    {
        $departments = User::whereNotNull('department')
            ->where('user_type', 'employee')
            ->select('department')
            ->distinct()
            ->pluck('department');

        $stats = [];
        
        foreach ($departments as $department) {
            $userIds = User::where('department', $department)->pluck('employee_number');
            
            $stats[$department] = [
                'total_employees' => User::where('department', $department)
                    ->where('user_type', 'employee')
                    ->count(),
                'total_appraisals' => Appraisal::whereIn('employee_number', $userIds)->count(),
                'approved_appraisals' => Appraisal::whereIn('employee_number', $userIds)
                    ->where('status', 'approved')
                    ->count(),
                'submitted_appraisals' => Appraisal::whereIn('employee_number', $userIds)
                    ->where('status', 'submitted')
                    ->count(),
                'average_score' => Appraisal::whereIn('employee_number', $userIds)
                    ->where('status', 'approved')
                    ->avg('overall_score') ?? 0,
            ];
        }

        return $stats;
    }

    /**
     * Get approval statistics for supervisor (updated for multiple supervisors)
     */
    private function getApprovalStats($user)
    {
        // Get employees this supervisor can rate
        $employeeNumbers = DB::table('employee_rating_supervisors')
            ->where('supervisor_id', $user->employee_number)
            ->pluck('employee_number');
        
        if ($employeeNumbers->isEmpty()) {
            return [
                'total' => 0,
                'approved' => 0,
                'pending' => 0,
                'approval_rate' => 0,
                'pending_rate' => 0
            ];
        }
        
        $totalAppraisals = Appraisal::whereIn('employee_number', $employeeNumbers)->count();
        $approvedAppraisals = Appraisal::whereIn('employee_number', $employeeNumbers)
            ->where('status', 'approved')->count();
        $pendingAppraisals = Appraisal::whereIn('employee_number', $employeeNumbers)
            ->where('status', 'submitted')->count();
        
        return [
            'total' => $totalAppraisals,
            'approved' => $approvedAppraisals,
            'pending' => $pendingAppraisals,
            'approval_rate' => $totalAppraisals > 0 ? round(($approvedAppraisals / $totalAppraisals) * 100, 1) : 0,
            'pending_rate' => $totalAppraisals > 0 ? round(($pendingAppraisals / $totalAppraisals) * 100, 1) : 0
        ];
    }

    /**
     * Get approval statistics for supervisor (using multiple supervisors system)
     */
    private function getApprovalStatsForSupervisor($supervisor)
    {
        // Get employees this supervisor can rate
        $employeeNumbers = DB::table('employee_rating_supervisors')
            ->where('supervisor_id', $supervisor->employee_number)
            ->pluck('employee_number');
        
        if ($employeeNumbers->isEmpty()) {
            return [
                'total' => 0,
                'approved' => 0,
                'pending' => 0,
                'approval_rate' => 0,
                'pending_rate' => 0
            ];
        }
        
        $totalAppraisals = Appraisal::whereIn('employee_number', $employeeNumbers)->count();
        $approvedAppraisals = Appraisal::whereIn('employee_number', $employeeNumbers)
            ->where('status', 'approved')->count();
        $pendingAppraisals = Appraisal::whereIn('employee_number', $employeeNumbers)
            ->where('status', 'submitted')->count();
        
        return [
            'total' => $totalAppraisals,
            'approved' => $approvedAppraisals,
            'pending' => $pendingAppraisals,
            'approval_rate' => $totalAppraisals > 0 ? round(($approvedAppraisals / $totalAppraisals) * 100, 1) : 0,
            'pending_rate' => $totalAppraisals > 0 ? round(($pendingAppraisals / $totalAppraisals) * 100, 1) : 0
        ];
    }
}