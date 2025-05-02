<?php

namespace App\Repositories;

use App\Models\Orange;
use App\Models\User;

class BucketRepository
{
    /**
     * Add or remove oranges from the bucket.
     *
     * @param int $userId
     * @param \App\Models\Orange $orange
     * @param bool $attach
     * @return void
     */
    public function updateBucket(int $userId, Orange $orange, bool $attach = true)
    {
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }

        if ($attach) $user->bucket()->attach($orange->id);
        else $user->bucket()->detach($orange->id);
    }
}
