<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::query()->updateOrCreate([
            'id' => 1,
            'title' => 'my post',
            'description' => 'post for test'
        ], [
            'title' => 'my post',
            'description' => 'post for test'
        ]);
    }
}
