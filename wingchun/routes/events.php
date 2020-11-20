<?php

use App\Events\SomeoneCancelledHisSession;
use App\Mail\VacancyAvailableMail;
use App\Models\User;
use Carbon\Carbon;
use \Illuminate\Support\Facades\Event;
use function Illuminate\Events\queueable;
use Illuminate\Support\Facades\Mail;

Event::listen(queueable(function (SomeoneCancelledHisSession $event) {
    $usersToNotify = User::whereNotIn('id', $event->session->attendees->pluck('id'))
        ->where('id', '<>', $event->user->id)
        ->get();
    $usersToNotify->each(function ($user) use ($event) {
        Mail::to($user->email)
            ->send(new VacancyAvailableMail($event->session, $user));
    });
}));
