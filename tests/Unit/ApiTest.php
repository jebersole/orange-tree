<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\OrangeTree;
use App\Models\Orange;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\OrangeRepository;
use App\Repositories\BucketRepository;
use App\Models\Season;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_fetch_seasons()
    {
        $response = $this->getJson('/api/seasons');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'current', // Ensure the response contains the current season
                 ]);
    }

    /** @test */
    public function it_can_fetch_orange_tree()
    {
        $tree = OrangeTree::factory()->create();

        $response = $this->getJson('/api/orange-tree');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'id',
                     'oranges' => [
                         '*' => ['id', 'on_ground'], // Ensure oranges have the correct structure
                     ],
                 ]);
    }

    /** @test */
    public function it_can_add_orange_to_bucket()
    {
        // Create a user and an orange
        $user = User::find(42);
        $orange = Orange::factory()->create();
    
        // Mock the repositories
        $this->mock(OrangeRepository::class, function ($mock) use ($orange) {
            $mock->shouldReceive('getOrangeById')
                 ->with($orange->id)
                 ->andReturn($orange);
        });
    
        $this->mock(BucketRepository::class, function ($mock) use ($user, $orange) {
            $mock->shouldReceive('updateBucket')
                 ->withArgs(function ($userId, $orangeArg) use ($user, $orange) {
                     return $userId === $user->id &&
                            $orangeArg->id === $orange->id;
                 })
                 ->once();
        });
    
        // Act: Call the update method
        $response = $this->actingAs($user)->putJson("/api/oranges/{$orange->id}");
    
        // Assert: Check the response
        $response->assertStatus(200)
                 ->assertJson(['message' => 'success']);
    }

    public function it_can_advance_season()
    {
        // Act: Call the advance season endpoint
        $response = $this->putJson('/api/seasons');
    
        // Assert: Check the response
        $response->assertStatus(200);
    
        // Retrieve the updated season
        $season = User::find(42)->season;
    
        // Assert: Check the structure of the response
        $response->assertJsonStructure([
            'current', // Ensure the response contains the updated season
        ]);
    
        // Assert: Verify the season has advanced
        $this->assertNotNull($season);
        $this->assertEquals(Season::SUMMER, $season->current); // Assuming it advances from SPRING to SUMMER
    }

    // Since there isn't yet user auth, and we just want the test user
    public function setUp(): void
    {
        parent::setUp();
        $user = User::find(42);
        if (!$user) {
            $user = User::factory()->create(['id' => 42]);
        }
    }
}