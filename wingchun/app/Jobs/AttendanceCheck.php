<?php

namespace App\Jobs;

use App\Models\Day;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AttendanceCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $endedSessions = Day::currentDay()->sessions->filter(function ($session) {
           return Carbon::parse($session->to_time)->isPast();
        });

        $endedSessions->each(function ($session) {
           $pivots = $session->attendees->map(function ($attendee) { return $attendee->pivot; });
           $pivots->each(function ($pivot) {
              if ($pivot->attendance_status == "booked") {
                  $pivot->attendance_status = "didnt_attend";
                  $pivot->save();
                  // TODO: ScoreKeeper::penalizeUserForNotComingToSession($user)
              }
           });
        });
    }
}
