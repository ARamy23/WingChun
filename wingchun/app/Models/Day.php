<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    static public function currentDay(): Day {
        return Day::firstWhere('date', '=', Carbon::today());
    }
}
