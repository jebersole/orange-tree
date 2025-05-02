<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected $userId;

    public function __construct()
    {
        // Example user id, in a real application this would be set based on the authenticated user
        // For example, you might use Laravel's Auth facade to get the authenticated user's ID
        $this->userId = 42;
    }
}
