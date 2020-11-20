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
        }, [['Thursday', true, Carbon::parse('today 12am')],
            ['Friday', false, Carbon::parse('today 12am')->addDays(1)],
            ['Saturday', false, Carbon::parse('today 12am')->addDays(2)],
            ['Sunday', false, Carbon::parse('today 12am')->addDays(3)],
            ['Monday', false, Carbon::parse('today 12am')->addDays(4)],
            ['Tuesday', false, Carbon::parse('today 12am')->addDays(5)],
            ['Wednesday', false, Carbon::parse('today 12am')->addDays(6)
            ]
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
