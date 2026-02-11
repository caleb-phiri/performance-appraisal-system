<?php

namespace App\Policies;

use App\Models\Appraisal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppraisalPolicy
{
    public function view(User $user, Appraisal $appraisal): bool
    {
        return $user->id === $appraisal->employee_id
            || $user->id === $appraisal->manager_id
            || $user->isAdmin();
    }

    public function update(User $user, Appraisal $appraisal): bool
    {
        return $user->id === $appraisal->employee_id
            && $appraisal->status === 'draft';
    }

    public function submit(User $user, Appraisal $appraisal): bool
    {
        return $user->id === $appraisal->employee_id
            && $appraisal->status === 'draft';
    }

    public function review(User $user, Appraisal $appraisal): bool
    {
        return $user->id === $appraisal->manager_id
            && $appraisal->status === 'submitted';
    }

    public function delete(User $user, Appraisal $appraisal): bool
    {
        return ($user->id === $appraisal->employee_id && $appraisal->status === 'draft')
            || $user->isAdmin();
    }
}
