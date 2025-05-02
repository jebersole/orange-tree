<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\OrangeTreeController;
use App\Http\Controllers\OrangeController;

// OrangeController routes
Route::get('/oranges', [OrangeController::class, 'index']); // List all oranges for a user
Route::put('/oranges/{id}', [OrangeController::class, 'update']); // Drag an orange to the bucket
Route::delete('/oranges/{id}', [OrangeController::class, 'destroy']); // Eat an orange from the bucket

// OrangeTreeController routes
Route::get('/orange-tree', [OrangeTreeController::class, 'show']); // Display the orange tree
Route::put('/orange-tree/{id}', [OrangeTreeController::class, 'update']); // Drag an orange to the bucket

// SeasonController routes
Route::get('/seasons', [SeasonController::class, 'show']); // Get the current season for a user
Route::put('/seasons', [SeasonController::class, 'update']); // Update a season
