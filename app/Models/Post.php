<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            if ($post->reports_count >= 5) {
                $post->is_approved = false;
                $post->is_reported = true;
            }
        });
    }

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'published_at',
        'is_approved',
        'is_reported',
        'reports_count',
        'image_path',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_approved'  => 'boolean',
        'is_reported'  => 'boolean',
        'reports_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }

    public function comments()
    {
        return $this->hasManyThrough(Comment::class, Conversation::class);
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
}

