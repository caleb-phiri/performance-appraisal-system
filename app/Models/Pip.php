<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pip extends Model
{
    use HasFactory;

    protected $table = 'pips';

    protected $fillable = [
        'employee_number',
        'initiated_by',
        'initiated_by_name',
        'pip_end_date',
        'notes',
    ];

    protected $casts = [
        'pip_end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_number', 'employee_number');
    }

    public function pipInitiator()
    {
        return $this->belongsTo(User::class, 'initiated_by', 'employee_number');
    }
}