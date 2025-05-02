<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 42,
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'), // Use bcrypt for hashing passwords
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
