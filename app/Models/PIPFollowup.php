<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PIPFollowup extends Model
{
    protected $table = 'pip_followups';
    
    protected $fillable = [
        'appraisal_id',
        'employee_number',
        'comment',
        'author_name',
        'author_type'
    ];
    
    public function appraisal()
    {
        return $this->belongsTo(Appraisal::class, 'appraisal_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_number', 'employee_number');
    }
}