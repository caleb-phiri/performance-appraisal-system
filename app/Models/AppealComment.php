<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppealComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appeal_id',
        'user_id',
        'comment',
        'is_supervisor'
    ];

    /**
     * Get the appeal that owns the comment
     */
    public function appeal()
    {
        return $this->belongsTo(Appeal::class);
    }

    /**
     * Get the user who made the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}