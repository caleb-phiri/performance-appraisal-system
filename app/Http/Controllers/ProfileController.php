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
    
    // Debug: Log the current user data
    Log::info('Profile loaded for user:', [
        'user_employee_number' => $user->employee_number,
        'manager_employee_number' => $user->manager_id,
        'toll_plazas_count' => count($tollPlazas),
        'toll_plazas' => $tollPlazas
    ]);
    
    return view('profile', compact('user', 'tollPlazas'));
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
            // Add more mappings as needed
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
            ->where('employee_number', '!=', $user->employee_number) // Exclude current user if they are a supervisor
            ->orderBy('name')
            ->get(['employee_number', 'name', 'department', 'job_title', 'email']);
        
        return response()->json([
            'success' => true,
            'supervisors' => $supervisors
        ]);
    }

    /**
     * Get toll plazas for dropdown (AJAX) - Optional
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
            // IMPORTANT: Since employee_number is primary key, use different validation
            $rules['employee_number'] = 'required|string|max:50|unique:users,employee_number,' . $user->employee_number . ',employee_number';
        } else {
            $rules['employee_number'] = 'required|string|max:50';
        }
        
        if ($emailChanged) {
            $rules['email'] = 'required|string|email|max:255|unique:users,email,' . $user->employee_number . ',employee_number';
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

        // Find manager by employee number if provided
        if ($user->user_type === 'employee' && $request->manager_id) {
            // Since employee_number is the primary key, we just store it directly
            $updateData['manager_id'] = $request->manager_id;
            
            Log::info('Manager set for employee:', [
                'employee' => $user->employee_number,
                'manager' => $request->manager_id
            ]);
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
     * Show password management form.
     */
    public function showPasswordForm()
    {
        $user = Auth::user();
        $requiresPasswordSetup = empty($user->password);
        return view('profile.password', compact('user', 'requiresPasswordSetup'));
    }

    /**
     * FIRST TIME PASSWORD SETUP
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

        // ✅ MATCHES YOUR FORM FIELD NAMES
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
     * SKIP PASSWORD SETUP
     */
    public function skipPasswordSetup()
    {
        $user = Auth::user();

        $user->update([
            'password_setup_skipped' => true,
        ]);

        return redirect()
            ->route('profile.show')
            ->with('info', 'You can set up your password later from your profile.');
    }

    /**
     * UPDATE EXISTING PASSWORD
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if (empty($user->password)) {
            return redirect()
                ->route('profile.password.form')
                ->with('info', 'Please set your first password.');
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
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
}