<?php

namespace Database\Seeders;

use App\Models\Day;
use App\Models\Session;
use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WeekWithSevenDays extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $week = Week::create();
        $days = array_map(function ($day) use ($week) {
            return Day::create([
                'name' => $day[0],
                'is_off_day' => $day[1],
                'date' => $day[2],
                'week_id' => $week->id
            ]);
        }, [['Thursday', true, Carbon::parse('Thursday 12am')],
            ['Friday', false, Carbon::parse('Friday 12am')],
            ['Saturday', false, Carbon::parse('Saturday 12am')],
            ['Sunday', false, Carbon::parse('Sunday 12am')],
            ['Monday', false, Carbon::parse('Monday 12am')],
            ['Tuesday', false, Carbon::parse('Tuesday 12am')],
            ['Wednesday', false, Carbon::parse('Wednesday 12am')],
        ]
        );

        $sessions = array_map(function ($day) {
            $session1 = Session::create([
                'from_time' => Carbon::parse($day->date)->setHour(18),
                'to_time' => Carbon::parse($day->date)->setHour(19),
                'day_id' => $day->id,
                'limit' => 6
            ]);

            $session2 = Session::create([
                'from_time' => Carbon::parse($day->date)->setHour(19),
                'to_time' => Carbon::parse($day->date)->setHour(20),
                'day_id' => $day->id,
                'limit' => 6
            ]);

            $session3 = Session::create([
                'from_time' => Carbon::parse($day->date)->setHour(20),
                'to_time' => Carbon::parse($day->date)->setHour(21),
                'day_id' => $day->id,
                'limit' => 6
            ]);
        }, array_filter($days, function ($day) {
            return !$day->is_off_day;
        }));
    }
}
