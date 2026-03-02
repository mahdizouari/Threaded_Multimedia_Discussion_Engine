<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reactable_id',
        'reactable_type',
        'user_id',
        'reacted_at',
    ];

    protected $casts = [
        'reacted_at' => 'datetime',
    ];

    public function reactable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appreciation()
    {
        return $this->hasOne(Appreciation::class);
    }
}

