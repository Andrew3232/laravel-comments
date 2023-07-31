<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class RepliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = Comment::all();
        $replies = Comment::factory(30)
            ->sequence(fn ($sequence) => ['parent_id' => $comments->random()])
            ->create();
    }
}
