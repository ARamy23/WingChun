<?php

namespace App\Models;

use App\Exceptions\NoMoreSlotsToBookException;
use App\Http\Controllers\Booker;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use phpDocumentor\Reflection\Types\Boolean;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'session_user')
            ->withTimestamps()
            ->withPivot('attendance_status');
    }

    public function didAttend(Session $session): bool
    {
        $session->attendees()->find($this->id)->pivot->refresh();
        return $session->attendees()->find($this->id)->pivot->attendance_status == 'attended';
    }

    public function didntAttend(Session $session): bool
    {
        $session->attendees()->find($this->id)->pivot->refresh();
        return $session->attendees()->find($this->id)->pivot->attendance_status == 'didnt_attend';
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
