<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\OrangeTree;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrangeTreeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_make_oranges()
    {
        $tree = OrangeTree::factory()->create();

        $tree->makeOranges();

        $this->assertCount(5, $tree->oranges); // Ensure 5 oranges are created
    }

    /** @test */
    public function it_can_drop_oranges()
    {
        $tree = OrangeTree::factory()->create();
        $tree->makeOranges();

        $tree->dropOranges();

        $this->assertTrue($tree->oranges->every(fn($orange) => $orange->on_ground));
    }

    /** @test */
    public function it_can_kill_oranges()
    {
        $tree = OrangeTree::factory()->create();
        $tree->makeOranges();

        $tree->killOranges();

        $this->assertCount(0, $tree->oranges); // Ensure all oranges are deleted
    }
}