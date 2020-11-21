<?php

namespace App\Http\Controllers;

use App\Exceptions\AttendingSessionAfterItHasEndedException;
use App\Exceptions\AttendingSessionBeforeItsTimeException;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;

class Attender
{
    static public function attend(User $user, Session $session)
    {
        self::makeSureSessionIsAttendable($session);
        $pivot = $session->attendees()->find($user->id)->pivot;
        $pivot->attendance_status = 'attended';
        $pivot->save();
        $user->refresh();
        $session->refresh();
    }

    static private function makeSureSessionIsAttendable(Session $session) {
        // now() < from_date - 15 && to_date < now()
        $from_time = Carbon::parse($session->from_time);
        $now = Carbon::now();
        $to_time = Carbon::parse($session->to_time);

        if ($now->lessThan($from_time->subMinutes(15))) {
            throw new AttendingSessionBeforeItsTimeException();
        } else if ($to_time->addMinutes(15)->lessThan($now)) {
            throw new AttendingSessionAfterItHasEndedException();
        }
    }
}
