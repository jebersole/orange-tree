<?php

namespace App\Http\Controllers;

use App\Repositories\OrangeTreeRepository;
use App\Repositories\BucketRepository;
use App\Repositories\OrangeRepository;

class OrangeTreeController extends Controller
{
    protected $orangeTreeRepository;
    protected $bucketRepository;
    protected $orangeRepository;

    public function __construct(OrangeRepository $orangeRepository, OrangeTreeRepository $orangeTreeRepository, BucketRepository $bucketRepository)
    {
        parent::__construct();
        $this->orangeTreeRepository = $orangeTreeRepository;
        $this->orangeRepository = $orangeRepository;
        $this->bucketRepository = $bucketRepository;
    }

    /**
     * Display the orange tree.
     * @return \Illuminate\Http\JsonResponse the orange tree
     */
    public function show()
    {
        $orangeTree = $this->orangeTreeRepository->getOrCreateOrangeTreeByUserId($this->userId);
        return response()->json($orangeTree);
    }

    /**
     * Pick an orange off the tree
     * @param string $id orange id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(string $id)
    {
        $orange = $this->orangeRepository->getOrangeById($id);
        if (!$orange) {
            return response()->json(['message' => 'Orange not found'], 400);
        }
        $this->orangeTreeRepository->updateOranges($this->userId, $orange, false);

        return response()->json(['message' => 'success']);
    }
}
