<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orange extends Model
{
    use HasFactory;

    protected $fillable = ['on_ground'];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function tree()
    {
        return $this->belongsToMany(OrangeTree::class);
    }
}
