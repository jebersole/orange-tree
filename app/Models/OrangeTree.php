<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangeTree extends Model
{
    public function oranges()
    {
        return $this->belongsToMany(Orange::class);
    }

    public function makeOranges()
    {
        $this->oranges()->attach(Orange::factory()->count(5)->create());
        $this->load('oranges');
    }

    public function dropOranges()
    {
        Orange::whereIn('id', $this->oranges()->get()->pluck('id')->toArray())
            ->update(['on_ground' => true]);
    }

    public function killOranges()
    {
        $this->oranges()->delete();
    }
}
