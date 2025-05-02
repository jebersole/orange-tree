<?php

namespace App\Http\Controllers;

use App\Repositories\SeasonRepository;

class SeasonController extends Controller
{
    protected $seasonRepo;

    public function __construct(SeasonRepository $seasonRepo)
    {
        $this->seasonRepo = $seasonRepo;
        parent::__construct();
    }

    /**
     * Show the current season for the user
     * @return \Illuminate\Http\JsonResponse the current season
     */
    public function show()
    {
        $season = $this->seasonRepo->getOrCreateSeasonByUserId($this->userId);
        return response()->json($season);
    }

    /**
     * Advance the season for the user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $this->seasonRepo->advanceSeason($this->userId);
        return response()->json(['message' => 'success']);
    }
}
