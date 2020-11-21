<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function book(Session $session)
    {
        $authenticatedUser = Auth::user();
        Booker::book($session, $authenticatedUser);
        return response()->json([
            'message' => 'Booking was successful.'
        ]);
    }

    public function getAllThisWeek() {
        return Week::currentSessionsThisWeek();
    }
}
