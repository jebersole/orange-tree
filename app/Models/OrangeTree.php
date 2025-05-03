<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrangeTree extends Model
{
    use HasFactory;

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
        $this->load('oranges');
    }

    public function killOranges()
    {
        $this->oranges()->delete();
        $this->load('oranges');
    }
}
