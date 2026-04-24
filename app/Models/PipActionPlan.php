<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PipActionPlan extends Model
{
    protected $fillable = [
        'appraisal_id',
        'action',
        'objective',
        'measurement',
        'sort_order'
    ];
    
    public function appraisal()
    {
        return $this->belongsTo(Appraisal::class);
    }
}