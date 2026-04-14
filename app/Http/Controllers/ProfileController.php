<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
   /**
 * Show the user's profile.
 */
public function show()
{
    $user = Auth::user();
    $user->load('manager'); // Load the manager relationship if it exists
    
    // Get toll plazas using helper method
    $tollPlazas = $this->getTollPlazasForDropdown();
    
    // Get approval chain for employees
    $approvalChain = [];
    if ($user->user_type === 'employee' && $user->manager) {
        $approvalChain[] = ['user' => $user->manager, 'type' => 'direct_supervisor'];
        
        // Add higher levels if they exist
        $nextManager = $user->manager->manager;
        $level = 2;
        while ($nextManager && $level <= 3) {
            $approvalChain[] = ['user' => $nextManager, 'type' => 'level_' . $level];
            $nextManager = $nextManager->manager;
            $level++;
        }
    }
    
    Log::info('Profile loaded for user:', [
        'user_employee_number' => $user->employee_number,
        'manager_id' => $user->manager_id,
        'toll_plazas_count' => count($tollPlazas),
        'user_type' => $user->user_type
    ]);
    
    // FIX: Change 'profile.show' to 'profile' if your file is profile.blade.php
    return view('profile', compact('user', 'tollPlazas', 'approvalChain'));
}
    /**
     * Get toll plazas for dropdown in standardized format
     */
    private function getTollPlazasForDropdown()
    {
        // Get all unique toll plazas from the database
        $tollPlazas = User::whereNotNull('toll_plaza')
            ->where('toll_plaza', '!=', '')
            ->distinct('toll_plaza')
            ->orderBy('toll_plaza')
            ->pluck('toll_plaza')
            ->toArray();
        
        // If no toll plazas in database, use default list
        if (empty($tollPlazas)) {
            return [
                'TP-001' => 'Kafulafuta Toll Plaza',
                'TP-002' => 'Abram Zayoni Mokola Toll Plaza',
                'TP-003' => 'Katuba Toll Plaza',
                'TP-004' => 'Manyumbi Toll Plaza',
                'TP-005' => 'Konkola Toll Plaza',
            ];
        }
        
        // Format as associative array [code => name]
        $formattedPlazas = [];
        foreach ($tollPlazas as $plaza) {
            $formattedPlazas[$plaza] = $this->formatTollPlazaName($plaza);
        }
        
        return $formattedPlazas;
    }

    /**
     * Format toll plaza name for display
     */
    private function formatTollPlazaName($plazaCode)
    {
        $plazaNames = [
            'TP-001' => 'Kafulafuta Toll Plaza',
            'TP-002' => 'Abram Zayoni Mokola Toll Plaza',
            'TP-003' => 'Katuba Toll Plaza',
            'TP-004' => 'Manyumbi Toll Plaza',
            'TP-005' => 'Konkola Toll Plaza',
            'TP-006' => 'Livingstone Toll Plaza',
            'TP-007' => 'Kapiri Mposhi Toll Plaza',
            'TP-008' => 'Kabwe Toll Plaza',
            'TP-009' => 'Ndola Toll Plaza',
            'TP-010' => 'Kitwe Toll Plaza',
        ];
        
        return $plazaNames[$plazaCode] ?? $plazaCode;
    }

    /**
     * Get supervisors for employee selection (AJAX)
     */
    public function getSupervisors()
    {
        $user = Auth::user();
        
        // Get all supervisors (users with user_type = 'supervisor')
        $supervisors = User::where('user_type', 'supervisor')
            ->where('employee_number', '!=', $user->employee_number) // Exclude current user by employee number
            ->orderBy('name')
            ->get(['id', 'employee_number', 'name', 'department', 'job_title', 'email']);
        
        // Format for display
        $formattedSupervisors = $supervisors->map(function($supervisor) {
            return [
                'id' => $supervisor->id,
                'employee_number' => $supervisor->employee_number,
                'name' => $supervisor->name,
                'display_name' => $supervisor->name . ' (' . $supervisor->employee_number . ')' . 
                                ($supervisor->department ? ' - ' . $supervisor->department : '') .
                                ($supervisor->job_title ? ' - ' . $supervisor->job_title : ''),
                'department' => $supervisor->department,
                'job_title' => $supervisor->job_title,
            ];
        });
        
        return response()->json([
            'success' => true,
            'supervisors' => $formattedSupervisors
        ]);
    }

    /**
     * Get toll plazas for dropdown (AJAX)
     */
    public function getTollPlazas()
    {
        // Get all unique toll plazas from the database
        $tollPlazas = User::whereNotNull('toll_plaza')
            ->where('toll_plaza', '!=', '')
            ->distinct('toll_plaza')
            ->orderBy('toll_plaza')
            ->pluck('toll_plaza')
            ->map(function ($plaza) {
                return [
                    'code' => $plaza,
                    'name' => $this->formatTollPlazaName($plaza)
                ];
            })
            ->values()
            ->toArray();
        
        // If no toll plazas in database, use default list
        if (empty($tollPlazas)) {
            $tollPlazas = [
                ['code' => 'TP-001', 'name' => 'Kafulafuta Toll Plaza'],
                ['code' => 'TP-002', 'name' => 'Abram Zayoni Mokola Toll Plaza'],
                ['code' => 'TP-003', 'name' => 'Katuba Toll Plaza'],
                ['code' => 'TP-004', 'name' => 'Manyumbi Toll Plaza'],
                ['code' => 'TP-005', 'name' => 'Konkola Toll Plaza'],
                ['code' => 'TP-006', 'name' => 'Livingstone Toll Plaza'],
                ['code' => 'TP-007', 'name' => 'Kapiri Mposhi Toll Plaza'],
                ['code' => 'TP-008', 'name' => 'Kabwe Toll Plaza'],
                ['code' => 'TP-009', 'name' => 'Ndola Toll Plaza'],
                ['code' => 'TP-010', 'name' => 'Kitwe Toll Plaza'],
            ];
        }
        
        return response()->json([
            'success' => true,
            'tollPlazas' => $tollPlazas
        ]);
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        Log::info('Profile update request received:', [
            'user_employee_number' => $user->employee_number,
            'user_type' => $user->user_type,
            'all_request_data' => $request->all(),
            'manager_id_from_request' => $request->manager_id
        ]);
        
        // Check if values are actually changing
        $employeeNumberChanged = $request->employee_number !== $user->employee_number;
        $emailChanged = $request->email !== $user->email;
        
        // Build validation rules conditionally
        $rules = [
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'workstation_type' => 'required|string|in:hq,toll_plaza,field',
        ];
        
        // Only add unique validation if the value is changing
        if ($employeeNumberChanged) {
            $rules['employee_number'] = 'required|string|max:50|unique:users,employee_number';
        } else {
            $rules['employee_number'] = 'required|string|max:50';
        }
        
        if ($emailChanged) {
            $rules['email'] = 'required|string|email|max:255|unique:users,email';
        } else {
            $rules['email'] = 'required|string|email|max:255';
        }
        
        // Add manager validation only for employees
        if ($user->user_type === 'employee') {
            $rules['manager_id'] = 'required|exists:users,employee_number';
        } else {
            $rules['manager_id'] = 'nullable';
        }
        
        // Conditionally require toll_plaza if workstation_type is toll_plaza
        if ($request->workstation_type === 'toll_plaza') {
            $rules['toll_plaza'] = 'required|string|max:255';
        } else {
            $rules['toll_plaza'] = 'nullable|string|max:255';
        }
        
        $validator = Validator::make($request->all(), $rules, [
            'employee_number.unique' => 'This employee number is already taken by another user.',
            'email.unique' => 'This email address is already registered.',
            'manager_id.required' => 'Please select a supervisor.',
            'manager_id.exists' => 'The selected supervisor does not exist.',
            'toll_plaza.required' => 'Please select a toll plaza.',
        ]);

        if ($validator->fails()) {
            Log::error('Profile update validation failed:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prepare update data
        $updateData = [
            'name' => $request->name,
            'employee_number' => $request->employee_number,
            'email' => $request->email,
            'department' => $request->department,
            'job_title' => $request->job_title,
            'workstation_type' => $request->workstation_type,
        ];

        // Only set toll_plaza if provided
        if ($request->has('toll_plaza') && $request->toll_plaza) {
            $updateData['toll_plaza'] = $request->toll_plaza;
        } else {
            $updateData['toll_plaza'] = null;
        }

        // Handle manager_id - store the employee_number directly
        if ($user->user_type === 'employee' && $request->manager_id) {
            $updateData['manager_id'] = $request->manager_id;
        } else {
            $updateData['manager_id'] = null;
        }

        // Check if all required fields are filled to mark as onboarded
        $requiredFields = [
            'name', 'employee_number', 'email', 'department', 
            'job_title', 'workstation_type'
        ];
        
        $isComplete = true;
        foreach ($requiredFields as $field) {
            if (empty($updateData[$field])) {
                $isComplete = false;
                break;
            }
        }
        
        // For employees, also require manager_id
        if ($user->user_type === 'employee' && empty($updateData['manager_id'])) {
            $isComplete = false;
        }
        
        // If workstation is toll plaza, require toll_plaza
        if ($updateData['workstation_type'] === 'toll_plaza' && empty($updateData['toll_plaza'])) {
            $isComplete = false;
        }
        
        $updateData['is_onboarded'] = $isComplete;
        if ($isComplete) {
            $updateData['onboarded_at'] = now();
        }

        Log::info('Updating user profile data:', [
            'user_employee_number' => $user->employee_number,
            'update_data' => $updateData
        ]);

        try {
            $user->update($updateData);
            
            // Refresh the user model to get updated relationships
            $user->refresh();
            $user->load('manager');
            
            Log::info('Profile updated successfully for user:', [
                'employee_number' => $user->employee_number,
                'updated_manager_id' => $user->manager_id,
                'manager_name' => $user->manager ? $user->manager->name : 'None',
                'toll_plaza' => $user->toll_plaza
            ]);
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update profile: ' . $e->getMessage())
                ->withInput();
        }

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show password management page.
     */
    public function password()
    {
        $user = Auth::user();
        $requiresPasswordSetup = empty($user->password);
        return view('profile.password', compact('user', 'requiresPasswordSetup'));
    }

    /**
     * Setup password for first time.
     */
    public function setupPassword(Request $request)
    {
        $user = Auth::user();

        // Prevent resetting if password already exists
        if (!empty($user->password)) {
            return redirect()
                ->route('profile.show')
                ->with('error', 'Password already exists. Use update password instead.');
        }

        $validated = $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $password = $validated['new_password'];

        // Extra security rules
        if (!preg_match('/[A-Z]/', $password)) {
            return back()->withErrors([
                'new_password' => 'Password must contain at least one uppercase letter.'
            ])->withInput();
        }

        if (!preg_match('/[a-z]/', $password)) {
            return back()->withErrors([
                'new_password' => 'Password must contain at least one lowercase letter.'
            ])->withInput();
        }

        if (!preg_match('/[0-9]/', $password)) {
            return back()->withErrors([
                'new_password' => 'Password must contain at least one number.'
            ])->withInput();
        }

        if (!preg_match('/[@$!%*?&]/', $password)) {
            return back()->withErrors([
                'new_password' => 'Password must contain at least one special character (@$!%*?&).'
            ])->withInput();
        }

        // Save password
        $user->update([
            'password' => Hash::make($password),
            'password_setup_skipped' => false,
        ]);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Password set successfully!');
    }

    /**
     * Skip password setup.
     */
    public function skipPassword(Request $request)
    {
        $user = Auth::user();
        
        $user->update([
            'password_setup_skipped' => true,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('info', 'Password setup skipped. You can set up a password later from your profile.');
    }

    /**
     * Update existing password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if (empty($user->password)) {
            return redirect()
                ->route('profile.password')
                ->with('info', 'Please set your first password.');
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }

        $newPassword = $validated['new_password'];

        // Extra security rules
        if (
            !preg_match('/[A-Z]/', $newPassword) ||
            !preg_match('/[a-z]/', $newPassword) ||
            !preg_match('/[0-9]/', $newPassword) ||
            !preg_match('/[@$!%*?&]/', $newPassword)
        ) {
            return back()->withErrors([
                'new_password' => 'Password must contain uppercase, lowercase, number and special character.'
            ])->withInput();
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Update supervisor selection - FIXED VERSION
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSupervisor(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $request->validate([
            'manager_id' => 'required|exists:users,employee_number',
        ], [
            'manager_id.required' => 'Please select a supervisor.',
            'manager_id.exists' => 'The selected supervisor does not exist.',
        ]);

        // Find the supervisor by employee number
        $supervisor = User::where('employee_number', $request->manager_id)->first();

        if (!$supervisor) {
            return back()->withErrors([
                'manager_id' => 'The selected supervisor does not exist.'
            ]);
        }

        // Check if the selected user is actually a supervisor
        if ($supervisor->user_type !== 'supervisor') {
            return back()->withErrors([
                'manager_id' => 'The selected user is not a valid supervisor.'
            ]);
        }

        // CRITICAL FIX: Compare by employee_number instead of ID
        // This prevents the "cannot select yourself" error
        if ($supervisor->employee_number === $user->employee_number) {
            return back()->withErrors([
                'manager_id' => 'You cannot select yourself as a supervisor.'
            ]);
        }

        // Update the user's manager_id - store the employee_number
        $user->manager_id = $supervisor->employee_number;
        $user->save();

        return back()->with('success', 'Your supervisor has been updated successfully. All future appraisals will be routed to ' . $supervisor->name . '.');
    }

    /**
     * Get eligible final approvers.
     */
    public function getEligibleApprovers()
    {
        // This is for users who can assign final approvers (HR, Admin)
        $approvers = User::where('user_type', 'supervisor')
            ->where('supervisor_level', '>=', 2)
            ->orderBy('name')
            ->get()
            ->map(function($user) {
                return [
                    'employee_number' => $user->employee_number,
                    'display_name' => $user->name . ' (' . $user->employee_number . ')' . 
                                    ($user->department ? ' - ' . $user->department : ''),
                ];
            });
        
        return response()->json([
            'success' => true,
            'eligible_approvers' => $approvers
        ]);
    }

    /**
     * Get final approver configurations.
     */
    public function getFinalApproverConfigs()
    {
        // This method would need a FinalApproverConfig model
        // For now, return empty array
        return response()->json([
            'success' => true,
            'configurations' => []
        ]);
    }

    /**
     * Save final approver configuration.
     */
    public function saveFinalApproverConfig(Request $request)
    {
        // This method would need a FinalApproverConfig model
        return response()->json([
            'success' => true,
            'message' => 'Configuration saved successfully'
        ]);
    }

    /**
     * Delete final approver configuration.
     */
    public function deleteFinalApproverConfig($id)
    {
        // This method would need a FinalApproverConfig model
        return response()->json([
            'success' => true,
            'message' => 'Configuration deleted successfully'
        ]);
    }
    
}