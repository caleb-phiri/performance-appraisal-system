<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Leave $leave)
    {
        return $user->employee_number === $leave->employee_number || 
               $user->user_type === 'supervisor';
    }

    public function update(User $user, Leave $leave)
    {
        return $user->employee_number === $leave->employee_number && 
               $leave->status === 'pending';
    }

    public function delete(User $user, Leave $leave)
    {
        return $user->employee_number === $leave->employee_number && 
               $leave->status === 'pending';
    }
}