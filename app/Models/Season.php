<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Season extends Model
{
    use HasFactory;
    const SPRING = 0;
    const SUMMER = 1;
    const AUTUMN = 2;
    const WINTER = 3;
    protected $fillable = ['current', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advance()
    {
        // Advance to the next season
        $this->current = ($this->current + 1) % 4; // Cycle through 0 (spring) to 3 (winter)
        $this->save();
        if ($this->current === self::WINTER) {
            $this->user->tree->killOranges();
        } elseif ($this->current === self::SPRING) {
            $this->user->tree->makeOranges();
        } elseif ($this->current === self::AUTUMN) {
            $this->user->tree->dropOranges();
        }
    }
}
