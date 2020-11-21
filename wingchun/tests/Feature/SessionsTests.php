<?php

namespace Tests\Feature;

use App\Models\Day;
use App\Models\Week;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SessionsTests extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    public function testUserCanBookASession()
    {
        $attendee = $this->signIn();
        $session = Day::all()->last()->sessions->last();

        $this->postJson($session->bookingURI())
            ->assertStatus(200)
            ->assertSee([
                'message' => 'Booking was successful.'
            ]);
    }

    public function testUserCanSeeSessionsThisWeek() {
        $this->signIn();

        $expectedSessions = Week::currentSessionsThisWeek();

        $this->getJson('/api/sessions/this-week')
            ->assertStatus(200)
            ->assertJson($expectedSessions->toArray());
    }
}
