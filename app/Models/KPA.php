<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpa extends Model
{
    use HasFactory;

    protected $fillable = [
        'appraisal_id',
        'category',
        'kpa',
        'result_indicators',
        'rating_description',
        'kpi',
        'weight',
        'self_rating',
        'self_percentage',
        'comments',
    ];

    // A KPA belongs to an appraisal
    public function appraisal()
    {
        return $this->belongsTo(Appraisal::class);
    }
}