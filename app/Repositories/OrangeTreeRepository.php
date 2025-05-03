<?php

namespace App\Repositories;

use App\Models\OrangeTree;
use App\Models\Orange;

class OrangeTreeRepository
{
    /**
     * Get the user's orange tree.
     *
     * @param int $userId
     * @return int
     */
    public function getOrangeTreeByUserId(int $userId): ?OrangeTree
    {
        return OrangeTree::where('user_id', $userId)->with('oranges')->get()->first();
    }

    /**
     * Create a new orange tree for the user.
     *
     * @param int $userId
     * @return \App\Models\OrangeTree
     */
    public function createOrangeTree(int $userId): OrangeTree
    {
        return OrangeTree::create(['user_id' => $userId]);
    }

    /**
     * Add or remove oranges from the tree.
     *
     * @param int $userId
     * @param \App\Models\Orange $orange
     * @param bool $attach
     * @return void
     */
    public function updateOranges(int $userId, Orange $orange, bool $attach = true)
    {
        $tree = OrangeTree::where('user_id', $userId)->get()->first();
        if (!$tree) {
            throw new \Exception('Tree not found');
        }

        if ($attach) $tree->oranges()->attach($orange->id);
        else $tree->oranges()->detach($orange->id);
    }
}
