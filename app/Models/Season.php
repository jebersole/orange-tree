<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    const SPRING = 0;
    const SUMMER = 1;
    const AUTUMN = 2;
    const WINTER = 3;
    protected $fillable = ['current', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
