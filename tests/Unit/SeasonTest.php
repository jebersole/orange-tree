<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Season;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeasonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_season_for_a_user()
    {
        $user = User::factory()->create();

        $season = Season::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $season->user_id);
    }

    /** @test */
    public function it_can_advance_to_the_next_season()
    {
        $season = Season::factory()->create(['current' => Season::SPRING]);

        $season->advance();

        $this->assertEquals(Season::SUMMER, $season->fresh()->current); // Assert against `current`
    }
}