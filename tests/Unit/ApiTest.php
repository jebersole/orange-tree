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
    const TEST_USER_ID = 42;

    public function it_can_create_a_season()
    {
        // Arrange: Create a user
        $user = User::factory()->create(['id' => self::TEST_USER_ID]);
    
        // Act: Call the create season endpoint
        $response = $this->postJson('/api/seasons', [
            'user_id' => $user->id,
            'current' => Season::SPRING, // Default to Spring
        ]);
    
        // Assert: Check the response
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id',
                     'user_id',
                     'current',
                     'created_at',
                     'updated_at',
                 ]);
    
        // Assert: Verify the season exists in the database
        $this->assertDatabaseHas('seasons', [
            'user_id' => $user->id,
            'current' => Season::SPRING,
        ]);
    }

    public function it_can_create_an_orange_tree()
    {
        // Arrange: Create a user
        $user = User::factory()->create(['id' => self::TEST_USER_ID]);
    
        // Act: Call the create orange tree endpoint
        $response = $this->postJson('/api/orange-tree', [
            'id' => 1, // Provide the required id field
            'user_id' => $user->id,
        ]);
    
        // Assert: Check the response
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id',
                     'user_id',
                     'created_at',
                     'updated_at',
                 ]);
    
        // Assert: Verify the orange tree exists in the database
        $this->assertDatabaseHas('orange_trees', [
            'id' => 1,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_fetch_seasons()
    {
        // Arrange: Create a user and a season
        $user = User::factory()->create(['id' => self::TEST_USER_ID]);
        Season::factory()->create(['user_id' => $user->id, 'current' => Season::SPRING]);
    
        // Act: Call the endpoint
        $response = $this->getJson('/api/seasons');
    
        // Assert: Check the response
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'current', // Ensure the response contains the current season
                 ]);
    }

    /** @test */
    public function it_can_fetch_orange_tree()
    {
        // Arrange: Create a user and an orange tree
        $user = User::factory()->create(['id' => self::TEST_USER_ID]);
        OrangeTree::factory()->create(['user_id' => $user->id]);
    
        // Act: Call the endpoint
        $response = $this->getJson('/api/orange-tree');
    
        // Assert: Check the response
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
        // Arrange: Create a user and an orange
        $user = User::factory()->create(['id' => self::TEST_USER_ID]);
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
        $response = $this->postJson('/api/oranges', ['id' => $orange->id]);
    
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
}
