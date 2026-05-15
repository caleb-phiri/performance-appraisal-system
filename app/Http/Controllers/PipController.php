<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PipController extends Controller
{
    /**
     * Display a listing of PIPs.
     */
    public function index(Request $request)
    {
        // Get the current logged-in user
        $user = auth()->user();
        
        // Build the query
        $query = Pip::with(['user', 'pipInitiator']);
        
        // Check user permissions
        $isAdmin = in_array($user->user_type, ['admin', 'Administrator', 'ADMIN']);
        $isSupervisor = $user->user_type === 'supervisor';
        $canViewAllPIPs = $user->can_view_pip ?? false;
        $canManageAllPIPs = $user->can_manage_pip ?? false;
        
        // Determine what PIPs the user can see
        if ($isAdmin || $canViewAllPIPs || $canManageAllPIPs) {
            // Admin or user with PIP view/manage permission - see ALL PIPs
            // No filtering needed
        } 
        elseif ($isSupervisor) {
            // Supervisor - only see PIPs for employees they supervise
            // Get all employee numbers under this supervisor
            $supervisedEmployees = User::where('manager_id', $user->employee_number)
                                       ->orWhere('supervisor_id', $user->id)
                                       ->orWhere('reporting_to', $user->employee_number)
                                       ->pluck('employee_number')
                                       ->toArray();
            
            // Also include PIPs created by this supervisor
            $query->where(function($q) use ($supervisedEmployees, $user) {
                $q->whereIn('employee_number', $supervisedEmployees)
                  ->orWhere('initiated_by', $user->employee_number)
                  ->orWhere('initiated_by_name', $user->name);
            });
        } 
        else {
            // Regular employee - only see their own PIPs
            $query->where('employee_number', $user->employee_number);
        }
        
        // Apply filters
        if ($request->has('status') && $request->status == 'active') {
            $query->where('pip_end_date', '>=', now());
        } elseif ($request->has('status') && $request->status == 'completed') {
            $query->where('pip_end_date', '<', now());
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('employee_number', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        if ($request->has('department') && !empty($request->department)) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('department', $request->department);
            });
        }
        
        // Get paginated results
        $pips = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Calculate statistics
        $stats = [
            'total' => Pip::count(),
            'active' => Pip::where('pip_end_date', '>=', now())->count(),
            'completed' => Pip::where('pip_end_date', '<', now())->count(),
        ];
        
        // Get departments for filter (only from users the current user can see)
        $departments = User::distinct()->pluck('department')->filter()->values()->toArray();
        
        return view('pip-management', compact('pips', 'stats', 'departments'));
    }

    /**
     * Show the form for creating a new PIP.
     */
    public function create()
    {
        $users = User::where('left_company', false)->orderBy('name')->get();
        return view('admin.pips.create', compact('users'));
    }

    /**
     * Store a newly created PIP in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|exists:users,employee_number',
            'pip_end_date' => 'required|date',
            'initiated_by' => 'nullable|string',
            'initiated_by_name' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $pip = Pip::create($validated);
            
            return redirect()->route('admin.pips.index')
                ->with('success', 'PIP created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating PIP: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create PIP. Please try again.');
        }
    }

    /**
     * Display the specified PIP.
     */
    public function show($id)
    {
        $pip = Pip::with(['user', 'pipInitiator'])->findOrFail($id);
        return view('admin.pips.show', compact('pip'));
    }

    /**
     * Show the form for editing the specified PIP.
     */
    public function edit($id)
    {
        $pip = Pip::findOrFail($id);
        $users = User::where('left_company', false)->orderBy('name')->get();
        return view('admin.pips.edit', compact('pip', 'users'));
    }

    /**
     * Update the specified PIP in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_number' => 'required|exists:users,employee_number',
            'pip_end_date' => 'required|date',
            'initiated_by' => 'nullable|string',
            'initiated_by_name' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $pip = Pip::findOrFail($id);
            $pip->update($validated);
            
            return redirect()->route('admin.pips.show', $pip->id)
                ->with('success', 'PIP updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating PIP: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to update PIP. Please try again.');
        }
    }

    /**
     * Remove the specified PIP from storage.
     */
    public function destroy($id)
    {
        try {
            $pip = Pip::findOrFail($id);
            $pip->delete();
            
            return redirect()->route('admin.pips.index')
                ->with('success', 'PIP deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting PIP: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete PIP. Please try again.');
        }
    }

    /**
     * Update PIP access for a user.
     */
    public function updateAccess(Request $request)
    {
        // Clear any output buffers that might exist
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        try {
            $request->validate([
                'employee_number' => 'required|string',
                'pip_access_level' => 'required|in:none,view,manage'
            ]);
            
            // Ensure columns exist
            if (!Schema::hasColumn('users', 'pip_access_level')) {
                Schema::table('users', function ($table) {
                    $table->string('pip_access_level')->default('none');
                    $table->boolean('can_view_pip')->default(false);
                    $table->boolean('can_manage_pip')->default(false);
                });
            }
            
            $user = User::where('employee_number', $request->employee_number)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false, 
                    'message' => 'User not found'
                ])->header('Content-Type', 'application/json');
            }
            
            // Update the PIP access fields
            $user->pip_access_level = $request->pip_access_level;
            $user->can_view_pip = in_array($request->pip_access_level, ['view', 'manage']);
            $user->can_manage_pip = $request->pip_access_level === 'manage';
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'PIP access updated successfully',
                'data' => [
                    'pip_access_level' => $user->pip_access_level,
                    'can_view_pip' => $user->can_view_pip,
                    'can_manage_pip' => $user->can_manage_pip
                ]
            ])->header('Content-Type', 'application/json');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . json_encode($e->errors())
            ], 422)->header('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            \Log::error('PIP access update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating PIP access: ' . $e->getMessage()
            ], 500)->header('Content-Type', 'application/json');
        }
    }
}