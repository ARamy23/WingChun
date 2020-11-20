<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'session_user')->withTimestamps();
    }

    public function day()
    {
        return $this->belongsTo(Day::class);
    }
}
