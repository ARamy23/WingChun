<?php

namespace App\Http\Controllers;

use App\Events\SomeoneCancelledHisSession;
use App\Exceptions\CancelSessionInSameDayException;
use App\Exceptions\NoMoreSlotsToBookException;
use App\Exceptions\SurpassedAllowedExcusesException;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;

class Booker
{

    static public function book(Session $sessionToBook, User $user) {
        self::makeSureSessionIsBookable($sessionToBook, $user);
        $sessionToBook->attendees()->attach($user);
    }

    private static function makeSureSessionIsBookable(Session $session, User $user)
    {
        if ($session->attendees()->count() >= $session->limit) throw new NoMoreSlotsToBookException();
    }

    static public function cancel(Session $sessionToCancel, User $user) {
        self::makeSureSessionIsCancellable($sessionToCancel, $user);
        self::handleExcuses($user, $sessionToCancel);
        $sessionToCancel->attendees()->detach($user);
        event(new SomeoneCancelledHisSession($sessionToCancel, $user));
    }

    private static function makeSureSessionIsCancellable(Session $session, User $user)
    {
        if ($user->allowed_excuses <= 0) throw new SurpassedAllowedExcusesException();

        $sessionIsToday = Carbon::parse($session->from_time)->isToday();
        $sessionBookedToday = Carbon::parse($session->attendees()->find($user->id)->created_at)->isToday();

        switch ([$sessionIsToday, $sessionBookedToday]) {
            case [true, false]:
                throw new CancelSessionInSameDayException();
            case [false, false]:
            case [false, true]:
            case [true, true]:
                return;
        }
    }

    private static function handleExcuses(User $user, Session $session) {

        $sessionIsToday = Carbon::parse($session->from_time)->isToday();
        $sessionBookedToday = Carbon::parse($session->attendees()->find($user->id)->created_at)->isToday();

        switch ([$sessionIsToday, $sessionBookedToday]) {
            case [true, false]:
                return;
            case [true, true]:
            case [false, false]:
                $user->excuses_count++;
                $user->allowed_excuses--;
                break;
            case [false, true]:
                $user->excuses_count++;
                break;
        }

        $user->save();
    }
}
