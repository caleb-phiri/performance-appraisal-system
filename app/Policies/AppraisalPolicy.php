<?php

namespace App\Policies;

use App\Models\Appraisal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppraisalPolicy
{
    public function view(User $user, Appraisal $appraisal)
    {
        return $user->id === $appraisal->employee_id || 
               $user->id === $appraisal->manager_id ||
               $user->role === 'admin';
    }

    public function update(User $user, Appraisal $appraisal)
    {
        return $user->id === $appraisal->employee_id;
    }

    public function review(User $user, Appraisal $appraisal)
    {
        return $user->id === $appraisal->manager_id;
    }

    public function delete(User $user, Appraisal $appraisal)
    {
        return $user->role === 'admin';
    }
       public function initiatePip(User $user, Appraisal $appraisal)
    {
        // Check if user is supervisor or HR/admin
        if (in_array($user->user_type, ['admin', 'hr'])) {
            return true;
        }
        
        if ($user->user_type !== 'supervisor') {
            return false;
        }
        
        // Check if user is assigned as supervisor for this employee
        $employee = $appraisal->user;
        if (!$employee) return false;
        
        // Check rating supervisors
        if ($employee->ratingSupervisors) {
            foreach ($employee->ratingSupervisors as $supervisor) {
                $supervisorId = $supervisor->employee_number ?? $supervisor->id;
                if ($supervisorId == $user->employee_number) {
                    return true;
                }
            }
        }
        
        // Check manager_id
        return $employee->manager_id === $user->employee_number;
    }
}