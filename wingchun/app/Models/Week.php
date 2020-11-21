<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    public function days()
    {
        return $this->hasMany(Day::class);
    }

    static public function currentWeek(): Week
    {

        return Week::firstWhere('date', '=', Carbon::now()->startOfWeek());
    }

    static public function currentSessionsThisWeek()
    {
        return Week::currentWeek()->days->map(function ($day) {
            return $day->sessions;
        })->flatten();
    }
}
