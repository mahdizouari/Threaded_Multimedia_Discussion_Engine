<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appreciation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reaction_id',
        'type', // TOP or FLOP
    ];

    public function reaction()
    {
        return $this->belongsTo(Reaction::class);
    }
}
