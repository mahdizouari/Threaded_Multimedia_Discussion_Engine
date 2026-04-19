<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'description',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function moderators()
    {
        return $this->belongsToMany(User::class);
    }

    public function interestedUsers()
    {
        return $this->belongsToMany(User::class, 'category_user_interests');
    }
}

