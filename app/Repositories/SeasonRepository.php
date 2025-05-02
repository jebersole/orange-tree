<?php

namespace App\Repositories;

use App\Models\Season;

class SeasonRepository
{
    /**
     * Create a new season for a user if it doesn't exist.
     *
     * @param int $userId
     * @return \App\Models\Season
     */
    public function getOrCreateSeasonByUserId(int $userId): Season
    {
        // Check if the season already exists
        $existingSeason = $this->getSeasonByUserId($userId);
        if ($existingSeason) {
            return $existingSeason;
        }

        // Create a new season if it doesn't exist
        Season::create([
            'user_id' => $userId,
            'current' => 0,
        ]);
        return $this->getSeasonByUserId($userId);
    }

    /**
     * Increment the current season for a specific user.
     *
     * @param int $userId
     * @return void
     */
    public function advanceSeason(int $userId)
    {
        $season = $this->getOrCreateSeasonByUserId($userId);
        if ($season->current === 3) $season->current = 0;
        else $season->current += 1;
        $season->save();
        if ($season->current === Season::WINTER) {
            $season->user->tree->killOranges();
        } elseif ($season->current === Season::SPRING) {
            $season->user->tree->makeOranges();
        } elseif ($season->current === Season::AUTUMN) {
            $season->user->tree->dropOranges();
        }
    }

    /**
     * Get the season for a specific user.
     *
     * @param int $userId
     * @return \App\Models\Season|null
     */
    public function getSeasonByUserId(int $userId): ?Season
    {
        return Season::where('user_id', $userId)->with('user.tree')->first();
    }
}
