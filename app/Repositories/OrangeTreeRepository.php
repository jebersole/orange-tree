<?php

namespace App\Repositories;

use App\Models\OrangeTree;
use App\Models\Orange;

class OrangeTreeRepository
{
    /**
     * Get the user's orange tree
     *
     * @param int $userId
     * @return int
     */
    public function getOrCreateOrangeTreeByUserId(int $userId): ?OrangeTree
    {
        $tree = OrangeTree::where('user_id', $userId)->with('oranges')->get()->first();
        if ($tree) return $tree;

        $tree = new OrangeTree;
        $tree->user_id = $userId;
        $tree->save();
        $tree->makeOranges();

        return $tree;
    }

    /**
     * Find an orange by its ID.
     *
     * @param int $id
     * @return \App\Models\Orange|null
     */
    public function getOrangeById(int $id): ?OrangeTree
    {
        return OrangeTree::find($id);
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
