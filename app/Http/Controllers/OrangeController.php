<?php

namespace App\Http\Controllers;

use App\Repositories\OrangeRepository;
use App\Repositories\BucketRepository;

class OrangeController extends Controller
{
    protected $orangeRepository;
    protected $bucketRepository;

    public function __construct(OrangeRepository $orangeRepository, BucketRepository $bucketRepository)
    {
        parent::__construct();
        $this->orangeRepository = $orangeRepository;
        $this->bucketRepository = $bucketRepository;
    }

    /**
     * Display all oranges for the user.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $oranges = $this->orangeRepository->getAllOrangesByUserId($this->userId);
        return response()->json($oranges);
    }


    /**
     * Drag an orange to the bucket
     * @param string $id orange id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(string $id)
    {
        $orange = $this->orangeRepository->getOrangeById($id);
        if (!$orange) {
            return response()->json(['message' => 'Orange not found'], 400);
        }

        $this->bucketRepository->updateBucket($this->userId, $orange);

        return response()->json(['message' => 'success']);
    }

    /**
     * Eat an orange from the bucket
     * @param string $id orange id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $orange = $this->orangeRepository->getOrangeById($id);
        if (!$orange) {
            return response()->json(['message' => 'Orange not found'], 400);
        }

        $this->bucketRepository->updateBucket($this->userId, $orange, false);

        return response()->json(['message' => 'success']);
    }
}
