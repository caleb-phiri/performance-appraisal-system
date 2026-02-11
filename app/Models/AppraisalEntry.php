<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AppraisalEntry extends Model
{
    protected $fillable = [
        'appraisal_id',
        'kpa_template_id',
        'self_rating',
        'manager_rating',
        'employee_comments',
        'manager_comments'
    ];

    // Relationships
    public function appraisal()
    {
        return $this->belongsTo(Appraisal::class);
    }

    public function kpaTemplate()
    {
        return $this->belongsTo(KpaTemplate::class);
    }

    // Helper Methods
    public function getEmployeeScoreAttribute()
    {
        if ($this->self_rating && $this->kpaTemplate) {
            return ($this->self_rating * $this->kpaTemplate->weight) / 100;
        }
        return 0;
    }

    public function getManagerScoreAttribute()
    {
        if ($this->manager_rating && $this->kpaTemplate) {
            return ($this->manager_rating * $this->kpaTemplate->weight) / 100;
        }
        return 0;
    }
}