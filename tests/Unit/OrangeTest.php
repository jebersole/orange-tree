<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Orange;
use App\Models\User;
use App\Models\OrangeTree;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrangeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_attributes()
    {
        $orange = new Orange();

        $this->assertEquals(['on_ground'], $orange->getFillable());
    }

    /** @test */
    public function it_belongs_to_many_users()
    {
        $orange = Orange::factory()->create();
        $user = User::factory()->create();

        $orange->user()->attach($user);

        $this->assertTrue($orange->user->contains($user));
    }

    /** @test */
    public function it_belongs_to_many_trees()
    {
        $orange = Orange::factory()->create();
        $tree = OrangeTree::factory()->create();

        $orange->tree()->attach($tree);

        $this->assertTrue($orange->tree->contains($tree));
    }
}