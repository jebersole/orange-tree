<?php

namespace App\Http\Controllers;

use App\Repositories\SeasonRepository;
use Illuminate\Http\Request;

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
        $season = $this->seasonRepo->getSeasonByUserId($this->userId);
        if (!$season) return response()->json(['message' => 'Season not found'], 400);

        return response()->json($season);
    }

    /**
     * Create a new season for the user
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        $id = $request->input('id');
        $season = $this->seasonRepo->getSeasonByUserId($id);
        if ($season) return response()->json(['message' => 'Season already exists'], 400);
        $season = $this->seasonRepo->createSeason($this->userId);

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
