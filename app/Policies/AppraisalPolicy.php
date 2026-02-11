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
}