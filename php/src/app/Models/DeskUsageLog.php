<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeskUsageLog extends Model
{
    protected $fillable = [
        'user_id',
        'desk_id',
        'position_mm',
        'position_type',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}