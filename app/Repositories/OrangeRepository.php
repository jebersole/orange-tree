<?php

namespace App\Repositories;

use App\Models\Orange;
use App\Models\User;

class OrangeRepository
{
    /**
     * Get all oranges for a specific user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllOrangesByUserId(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('id', $userId)->with('bucket')->first()->bucket;
    }

    /**
     * Find an orange by its ID.
     *
     * @param int $id
     * @return \App\Models\Orange|null
     */
    public function getOrangeById(int $id): ?Orange
    {
        return Orange::find($id);
    }
}
