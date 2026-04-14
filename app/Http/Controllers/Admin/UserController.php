<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

class UserController extends Controller
{
    /**
 * Display a listing of active users.
 */
public function index()
{
    try {
        // Debug logging
        Log::info('User index called, checking left_company column');
        
        // Check if left_company column exists
        if (Schema::hasColumn('users', 'left_company')) {
            Log::info('left_company column exists, filtering users');
            
            // Get users who haven't left company AND are active
            $users = User::where('left_company', false)
                        ->orderBy('name')
                        ->paginate(20);
            
            // Debug: Count how many users are marked as left
            $leftCount = User::where('left_company', true)->count();
            Log::info("Users marked as left company: {$leftCount}");
            Log::info("Active users retrieved: " . $users->count());
            
        } else {
            Log::warning('left_company column does not exist, showing all users');
            // Fallback: show all users
            $users = User::orderBy('name')->paginate(20);
        }
        
        // Calculate FULL statistics (not just from the paginated collection)
        $stats = $this->calculateFullStats();
        
        return view('admin.users.index', compact('users', 'stats'));
    } catch (\Exception $e) {
        Log::error('Error fetching users: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->route('admin.dashboard')
            ->with('error', 'Failed to load users. Please try again.');
    }
}

/**
 * Calculate full statistics from entire database
 */
private function calculateFullStats()
{
    try {
        $stats = [
            'total_users' => User::count(),
            'active_users' => 0,
            'left_company_users' => 0,
            'supervisors' => 0,
            'employees' => 0,
            'users_without_password' => 0,
        ];

        // Check if left_company column exists
        if (Schema::hasColumn('users', 'left_company')) {
            // Count users who left company - query entire database
            $stats['left_company_users'] = User::where('left_company', true)->count();
            
            // Count active users (not left company)
            $stats['active_users'] = User::where('left_company', false)->count();
            
            // Other stats only for active users
            $stats['supervisors'] = User::where('user_type', 'supervisor')
                                      ->where('left_company', false)
                                      ->count();
            $stats['employees'] = User::where('user_type', 'employee')
                                    ->where('left_company', false)
                                    ->count();
            $stats['users_without_password'] = User::whereNull('password')
                                                 ->where('left_company', false)
                                                 ->count();
        } else {
            // If column doesn't exist, calculate without left_company filter
            $stats['active_users'] = User::count();
            $stats['supervisors'] = User::where('user_type', 'supervisor')->count();
            $stats['employees'] = User::where('user_type', 'employee')->count();
            $stats['users_without_password'] = User::whereNull('password')->count();
        }
        
        Log::info('Full stats calculated', $stats);
        
        return $stats;
    } catch (\Exception $e) {
        Log::error('Error calculating full stats: ' . $e->getMessage());
        return [
            'total_users' => 0,
            'active_users' => 0,
            'left_company_users' => 0,
            'supervisors' => 0,
            'employees' => 0,
            'users_without_password' => 0,
        ];
    }
}
    /**
     * Display a listing of inactive users (left company).
     */
    public function inactive()
    {
        try {
            Log::info('Inactive users page accessed');
            
            // First ensure all required columns exist
            $this->ensureAllLeftCompanyColumns();
            
            // Check if left_company column exists
            if (Schema::hasColumn('users', 'left_company')) {
                Log::info('Fetching users who left company');
                
                $inactiveUsers = User::where('left_company', true)
                                    ->orderBy('left_at', 'desc')
                                    ->paginate(20);
                
                Log::info('Found ' . $inactiveUsers->count() . ' inactive users');
                
                // If no users found, check if any users have left_company = true
                if ($inactiveUsers->count() === 0) {
                    $totalLeft = User::where('left_company', true)->count();
                    Log::info("Total users with left_company=true: {$totalLeft}");
                }
                
            } else {
                Log::warning('left_company column does not exist');
                // If column doesn't exist, show empty
                $inactiveUsers = User::where('id', 0)->paginate(20);
            }
            
            return view('admin.users.inactive', compact('inactiveUsers'));
        } catch (\Exception $e) {
            Log::error('Error fetching inactive users: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to load inactive users. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Get supervisors for dropdown
        $supervisors = User::where('user_type', 'supervisor')
                          ->where(function($query) {
                              if (Schema::hasColumn('users', 'left_company')) {
                                  $query->where('left_company', false);
                              }
                          })
                          ->orderBy('name')
                          ->get();
        
        return view('admin.users.create', compact('supervisors'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|string|unique:users,employee_number',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'job_title' => 'required|string|max:255',
            'user_type' => 'required|in:employee,supervisor',
            'department' => 'required|string',
            'workstation_type' => 'required|in:hq,toll_plaza',
            'hq_department' => 'required_if:workstation_type,hq',
            'toll_plaza' => 'required_if:workstation_type,toll_plaza',
            'manager_id' => 'nullable|exists:users,employee_number',
            'num_employees' => 'nullable|integer|min:0',
        ]);

        try {
            // Create user
            $userData = array_merge($validated, [
                'is_active' => true,
                'is_onboarded' => false,
                'password_setup_skipped' => false,
            ]);

            // Add left_company field if it exists
            if (Schema::hasColumn('users', 'left_company')) {
                $userData['left_company'] = false;
            }

            $user = User::create($userData);

            // Set temporary password if provided
            if ($request->has('temp_password') && $request->temp_password) {
                $user->setPassword($request->temp_password);
                $user->save();
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create user. Please try again.');
        }
    }

   public function show($employee_number)
{
    $user = User::where('employee_number', $employee_number)->firstOrFail();
    
    // Always set teamMembers as a collection (empty if not supervisor)
    $teamMembers = collect([]);
    
    if ($user->user_type === 'supervisor') {
        // Try different possible supervisor relationships
        $teamMembers = User::where(function($query) use ($user) {
                $query->where('reporting_to', $user->employee_number)
                    ->orWhere('supervisor_id', $user->id)
                    ->orWhere('supervisor', $user->name)
                    ->orWhere('supervisor_employee_number', $user->employee_number);
            })
            ->where('left_company', false)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }
    
    return view('admin.users.show', compact('user', 'teamMembers'));
}

    /**
     * Show the form for editing the specified user.
     */
    public function edit($employeeNumber)
    {
        try {
            $user = User::where('employee_number', $employeeNumber)->firstOrFail();
            $supervisors = User::where('user_type', 'supervisor')
                              ->where('employee_number', '!=', $employeeNumber)
                              ->where(function($query) {
                                  if (Schema::hasColumn('users', 'left_company')) {
                                      $query->where('left_company', false);
                                  }
                              })
                              ->orderBy('name')
                              ->get();
            
            return view('admin.users.edit', compact('user', 'supervisors'));
        } catch (\Exception $e) {
            Log::error('Error fetching user for edit: ' . $e->getMessage());
            return redirect()->route('admin.users.index')
                ->with('error', 'User not found.');
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $employeeNumber)
    {
        try {
            $user = User::where('employee_number', $employeeNumber)->firstOrFail();
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $user->id,
                'job_title' => 'required|string|max:255',
                'user_type' => 'required|in:employee,supervisor',
                'department' => 'required|string',
                'workstation_type' => 'required|in:hq,toll_plaza',
                'hq_department' => 'required_if:workstation_type,hq',
                'toll_plaza' => 'required_if:workstation_type,toll_plaza',
                'manager_id' => 'nullable|exists:users,employee_number',
                'num_employees' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
            ]);

            $user->update($validated);

            return redirect()->route('admin.users.show', $user->employee_number)
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    /**
     * Show the form for marking a user as left company.
     */
    public function showMarkLeftForm($employeeNumber)
    {
        try {
            $user = User::where('employee_number', $employeeNumber)->firstOrFail();
            
            // Check if already marked as left
            if (Schema::hasColumn('users', 'left_company') && $user->left_company) {
                return redirect()->route('admin.users.show', $user->employee_number)
                    ->with('error', 'This user is already marked as left company.');
            }
            
            return view('admin.users.mark-left', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error loading mark-left form: ' . $e->getMessage());
            return redirect()->route('admin.users.index')
                ->with('error', 'User not found.');
        }
    }

    /**
     * Mark a user as having left the company.
     */
    public function markAsLeft(Request $request, $employeeNumber)
    {
        Log::info('markAsLeft method called', [
            'employee_number' => $employeeNumber,
            'request_data' => $request->all()
        ]);

        // First ensure all required columns exist
        $this->ensureAllLeftCompanyColumns();
        
        $request->validate([
            'left_reason' => 'required|in:resignation,termination,retirement,end_of_contract,transfer,other',
            'left_notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            
            $user = User::where('employee_number', $employeeNumber)->firstOrFail();
            
            Log::info('User found', [
                'user_id' => $user->id,
                'employee_number' => $user->employee_number,
                'current_left_company' => $user->left_company ?? 'null'
            ]);
            
            // Check if already left
            if ($user->left_company ?? false) {
                Log::warning('User already marked as left company', [
                    'user_id' => $user->id,
                    'employee_number' => $user->employee_number
                ]);
                
                return redirect()->route('admin.users.show', $user->employee_number)
                    ->with('error', 'This user is already marked as left company.');
            }
            
            $updateData = [
                'is_active' => 0, // Use integer for SQLite
                'updated_at' => now(),
            ];
            
            // Only add these fields if they exist
            if (Schema::hasColumn('users', 'left_company')) {
                $updateData['left_company'] = 1; // Use integer for SQLite
            }
            
            if (Schema::hasColumn('users', 'left_at')) {
                $updateData['left_at'] = now();
            }
            
            if (Schema::hasColumn('users', 'left_reason')) {
                $updateData['left_reason'] = $request->left_reason;
            }
            
            if (Schema::hasColumn('users', 'left_notes')) {
                $updateData['left_notes'] = $request->left_notes;
            }
            
            Log::info('Attempting to update user with data:', $updateData);
            
            $result = $user->update($updateData);
            
            if (!$result) {
                Log::error('Update failed for user', [
                    'user_id' => $user->id,
                    'update_data' => $updateData
                ]);
                throw new \Exception('User update failed');
            }
            
            DB::commit();
            
            Log::info('User marked as left company successfully', [
                'user_id' => $user->id,
                'employee_number' => $user->employee_number,
                'name' => $user->name
            ]);
            
            return redirect()->route('admin.users.index')
                ->with('success', "{$user->name} has been marked as left the company.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error marking user as left: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'employee_number' => $employeeNumber
            ]);
            
            return back()->withInput()
                ->with('error', 'Failed to mark user as left company. Error: ' . $e->getMessage());
        }
    }

    /**
     * Ensure all required columns for left company functionality exist.
     */
    private function ensureAllLeftCompanyColumns()
    {
        try {
            $requiredColumns = [
                'left_company' => 'BOOLEAN DEFAULT 0',
                'left_at' => 'TIMESTAMP NULL',
                'left_reason' => 'VARCHAR(50) NULL',
                'left_notes' => 'TEXT NULL',
            ];
            
            foreach ($requiredColumns as $column => $definition) {
                if (!Schema::hasColumn('users', $column)) {
                    try {
                        DB::statement("ALTER TABLE users ADD COLUMN {$column} {$definition}");
                        Log::info("Added missing column: {$column}");
                    } catch (\Exception $e) {
                        Log::error("Failed to add column {$column}: " . $e->getMessage());
                        // Continue with other columns
                    }
                }
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to ensure columns: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reactivate a user account.
     */
    public function reactivate($employeeNumber)
    {
        // First ensure all required columns exist
        $this->ensureAllLeftCompanyColumns();

        try {
            $user = User::where('employee_number', $employeeNumber)->firstOrFail();
            
            $updateData = [
                'is_active' => 1,
            ];
            
            // Only update these fields if they exist
            if (Schema::hasColumn('users', 'left_company')) {
                $updateData['left_company'] = 0;
            }
            
            if (Schema::hasColumn('users', 'left_at')) {
                $updateData['left_at'] = null;
            }
            
            if (Schema::hasColumn('users', 'left_reason')) {
                $updateData['left_reason'] = null;
            }
            
            if (Schema::hasColumn('users', 'left_notes')) {
                $updateData['left_notes'] = null;
            }
            
            $user->update($updateData);
            
            return redirect()->route('admin.users.index')
                ->with('success', "{$user->name}'s account has been reactivated.");
        } catch (\Exception $e) {
            Log::error('Error reactivating user: ' . $e->getMessage());
            return back()
                ->with('error', 'Failed to reactivate user. Please try again.');
        }
    }

    /**
     * Reset user password.
     */
    public function resetPassword($employeeNumber)
    {
        try {
            $user = User::where('employee_number', $employeeNumber)->firstOrFail();
            
            return view('admin.users.reset-password', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error loading reset password form: ' . $e->getMessage());
            return redirect()->route('admin.users.index')
                ->with('error', 'User not found.');
        }
    }

    /**
     * Process password reset.
     */
    public function updatePassword(Request $request, $employeeNumber)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ]);

        try {
            $user = User::where('employee_number', $employeeNumber)->firstOrFail();
            
            $user->setPassword($request->new_password);
            $user->save();
            
            return redirect()->route('admin.users.show', $user->employee_number)
                ->with('success', 'Password reset successfully.');
        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to reset password. Please try again.');
        }
    }

   public function search(Request $request)
{
    try {
        $search = $request->input('search', '');
        
        // Build query
        $query = User::query();
        
        // Only show active users by default
        if (Schema::hasColumn('users', 'left_company')) {
            $query->where('left_company', false);
        }
        
        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('employee_number', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('department', 'LIKE', "%{$search}%")
                  ->orWhere('job_title', 'LIKE', "%{$search}%");
            });
        }
        
        // Order and paginate
        $users = $query->orderBy('name')->paginate(10);
        
        // Calculate stats
        $stats = $this->calculateFullStats();
        
        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.users.partials.users_table', compact('users'))->render(),
                'stats' => $stats,
                'showing' => $users->count(),
                'total' => $users->total()
            ]);
        }
        
        // For non-AJAX requests
        return view('admin.users.index', compact('users', 'search', 'stats'));
        
    } catch (\Exception $e) {
        Log::error('Error searching users: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->route('admin.users.index')
            ->with('error', 'Search failed. Please try again.');
    }
}
    /**
     * Export users to Excel.
     */
    public function export()
    {
        try {
            return Excel::download(new UsersExport, 'users-' . date('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            Log::error('Error exporting users: ' . $e->getMessage());
            return back()->with('error', 'Failed to export users. Please try again.');
        }
    }

    /**
     * Bulk import users from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,xlsx,xls',
        ]);

        try {
            $file = $request->file('import_file');
            $import = new UsersImport;
            Excel::import($import, $file);
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Users imported successfully.');
        } catch (\Exception $e) {
            Log::error('Error importing users: ' . $e->getMessage());
            return back()->with('error', 'Failed to import users. Please check the file format.');
        }
    }

    /**
     * Show import form.
     */
    public function showImportForm()
    {
        return view('admin.users.import');
    }

    /**
     * Delete user (soft delete or permanent).
     */
    public function destroy($employeeNumber)
    {
        try {
            $user = User::where('employee_number', $employeeNumber)->firstOrFail();
            
            // Ensure columns exist first
            $this->ensureAllLeftCompanyColumns();
            
            // Check if left_company column exists
            if (Schema::hasColumn('users', 'left_company')) {
                if ($user->left_company) {
                    // Permanent delete for users who left company
                    $user->forceDelete();
                    $message = 'User permanently deleted.';
                } else {
                    // Soft delete for active users (mark as left company)
                    $user->update([
                        'left_company' => true,
                        'left_at' => now(),
                        'left_reason' => 'termination',
                        'is_active' => false,
                    ]);
                    $message = 'User marked as left company.';
                }
            } else {
                // If no left_company field, just delete
                $user->delete();
                $message = 'User deleted.';
            }
            
            return redirect()->route('admin.users.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete user. Please try again.');
        }
    }

    /**
     * Get user statistics.
     */
    public function statistics()
    {
        try {
            $stats = $this->calculateStats();
            
            Log::info('Statistics calculated', $stats);
            
            return view('admin.users.statistics', compact('stats'));
        } catch (\Exception $e) {
            Log::error('Error fetching statistics: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to load statistics. Please try again.');
        }
    }

    /**
     * Force reset all passwords (admin function).
     */
    public function forceResetAllPasswords(Request $request)
    {
        $request->validate([
            'confirm' => 'required|accepted',
        ]);

        try {
            // Check if left_company column exists
            if (Schema::hasColumn('users', 'left_company')) {
                $users = User::where('left_company', false)->get();
            } else {
                $users = User::all();
            }
            
            $count = 0;
            foreach ($users as $user) {
                if (!$user->hasHashedPassword()) {
                    $user->convertToHash();
                    $count++;
                }
            }
            
            return redirect()->route('admin.users.index')
                ->with('success', "{$count} user passwords have been converted to secure hashes.");
        } catch (\Exception $e) {
            Log::error('Error force resetting passwords: ' . $e->getMessage());
            return back()->with('error', 'Failed to reset passwords. Please try again.');
        }
    }

    /**
     * Debug method to check database columns
     */
    public function debugSchema()
    {
        $columns = [];
        if (Schema::hasTable('users')) {
            $columns = Schema::getColumnListing('users');
        }
        
        $users = User::all()->map(function($user) {
            return [
                'employee_number' => $user->employee_number,
                'name' => $user->name,
                'left_company' => $user->left_company ?? 'null',
                'left_at' => $user->left_at ?? 'null',
                'left_reason' => $user->left_reason ?? 'null',
                'is_active' => $user->is_active,
            ];
        });
        
        return response()->json([
            'has_left_company_column' => in_array('left_company', $columns),
            'all_columns' => $columns,
            'database_connection' => config('database.default'),
            'users_count' => $users->count(),
            'left_company_count' => User::where('left_company', true)->count(),
            'users' => $users
        ]);
    }
    public function makeSupervisor(Request $request, $employeeNumber)
{
    try {
        $user = User::where('employee_number', $employeeNumber)->firstOrFail();
        
        // Check if already a supervisor
        if ($user->user_type === 'supervisor') {
            return response()->json([
                'success' => false,
                'message' => 'User is already a supervisor.'
            ], 400);
        }
        
        // Check if admin (can't demote admin)
        if ($user->user_type === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change admin role.'
            ], 400);
        }
        
        // Only update the user_type, don't update department
        $user->user_type = 'supervisor';
        
        // Only update department if it's provided and not empty
        if ($request->has('department') && !empty($request->department)) {
            $user->department = $request->department;
        }
        // If no department provided, keep the existing department or set a default
        // else, leave it as is (don't update)
        
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => "{$user->name} is now a supervisor.",
            'user' => $user
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Failed to promote user to supervisor: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to promote user: ' . $e->getMessage()
        ], 500);
    }
}
}