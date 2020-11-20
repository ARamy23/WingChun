<?php

namespace App\Mail;

use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VacancyAvailableMail extends Mailable
{
    use Queueable, SerializesModels;

    private User $cancellingUser;
    private Session $session;

    /**
     * Create a new message instance.
     *
     * @param Session $session
     * @param User $cancellingUser
     */
    public function __construct(Session $session, User $cancellingUser)
    {
        $this->session = $session;
        $this->cancellingUser = $cancellingUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $readableSessionFromDate = Carbon::parse($this->session->from_time)->format('l, j, F, g A');
        $readableSessionToDate = Carbon::parse($this->session->to_time)->format('l, j, F, g A');
        return $this->markdown('emails.vacancy-available')->with([
            'user' => $this->cancellingUser,
            'readableSessionFromDate' => $readableSessionFromDate,
            'readableSessionToDate' => $readableSessionToDate,
        ]);
    }
}
