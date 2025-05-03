<?php

namespace App\Repositories;

use App\Models\Season;

class SeasonRepository
{
    /**
     * Create a season for a specific user.
     *
     * @param int $userId
     * @return \App\Models\Season|null
     */
    public function createSeason(int $userId): Season
    {
        return Season::create(['user_id' => $userId]);
    }

    /**
     * Increment the current season for a specific user.
     *
     * @param int $userId
     * @return void
     */
    public function advanceSeason(int $userId)
    {
        $season = $this->getSeasonByUserId($userId);
        $season->advance();
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
