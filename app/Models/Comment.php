<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'text',
        'commented_at',
        'is_reported',
        'reports_count',
        'report_reason',
    ];

    protected $casts = [
        'commented_at' => 'datetime',
        'is_reported' => 'boolean',
        'reports_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($comment) {
            if ($comment->reports_count >= 5) {
                $comment->is_reported = true;
            }
        });
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
}
