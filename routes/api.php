<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\OrangeTreeController;
use App\Http\Controllers\OrangeController;

// OrangeController routes
Route::get('/oranges', [OrangeController::class, 'index']); // List all oranges for a user
Route::post('/oranges', [OrangeController::class, 'update']); // Drag an orange to the bucket
Route::delete('/oranges/{id}', [OrangeController::class, 'destroy']); // Eat an orange from the bucket

// OrangeTreeController routes
Route::get('/orange-tree', [OrangeTreeController::class, 'show']); // Display the orange tree
Route::put('/orange-tree', [OrangeTreeController::class, 'create']); // Create a new orange tree
Route::delete('/orange-tree/{id}', [OrangeTreeController::class, 'destroy']); // Pick an orange off the tree

// SeasonController routes
Route::get('/seasons', [SeasonController::class, 'show']); // Get the current season for a user
Route::put('/seasons', [SeasonController::class, 'create']); // Create a season
Route::post('/seasons', [SeasonController::class, 'update']); // Advance the season
