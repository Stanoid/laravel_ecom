<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Post::factory(10)->create();

      
        Post::factory(100)->create([
            'title' => 'Test User',
            'body' => 'test@example.com',
            'user_id'=>1
        ]);
    }
}
