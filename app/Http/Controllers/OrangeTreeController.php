<?php

namespace App\Http\Controllers;

use App\Repositories\OrangeTreeRepository;
use App\Repositories\BucketRepository;
use App\Repositories\OrangeRepository;
use Illuminate\Http\Request;

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
        $orangeTree = $this->orangeTreeRepository->getOrangeTreeByUserId($this->userId);
        if (!$orangeTree) return response()->json(['message' => 'Orange tree not found'], 400);

        return response()->json($orangeTree);
    }

    /**
     * Create a new orange tree.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        $id = $request->input('id');
        $orangeTree = $this->orangeTreeRepository->getOrangeTreeByUserId($id);
        if ($orangeTree) return response()->json(['message' => 'Orange tree already exists'], 400);
        $orangeTree = $this->orangeTreeRepository->createOrangeTree($this->userId);

        return response()->json($orangeTree);
    }

    /**
     * Pick an orange off the tree
     * @param string $id orange id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $orange = $this->orangeRepository->getOrangeById($id);
        if (!$orange) {
            return response()->json(['message' => 'Orange not found'], 400);
        }
        $this->orangeTreeRepository->updateOranges($this->userId, $orange, false);

        return response()->json(['message' => 'success']);
    }
}
