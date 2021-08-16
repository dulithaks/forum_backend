<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create([
            'id' => 1,
            'email' => 'admin@example.net',
            'role' => 'admin',
            'password' => bcrypt('12345678'),
            'api_token' => Str::random(20),
        ]);

        \App\Models\User::factory(1)->create([
            'id' => 2,
            'email' => 'user@example.net',
            'role' => 'user',
            'password' => bcrypt('12345678'),
            'api_token' => Str::random(20),
        ]);

        \App\Models\User::factory(10)->create();
        \App\Models\Post::factory(50)->create();
        \App\Models\Comment::factory(500)->create();
    }
}
