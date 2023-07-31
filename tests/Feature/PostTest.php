<?php

namespace Tests\Feature;

use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private mixed $post;

    public function setUp(): void
    {
        parent::setUp();

        $this->post = Post::create([
            'title' => 'test Title',
            'description' => 'Test description',
        ]);
    }

    public function testGetPostList(): void
    {
        $response = $this->getJson(route('posts.index'));

        $response->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'description',
                    'created_at',
                ]
            ])
            ->assertJsonFragment([
                'id' => $this->post->id,
                'title' => $this->post->title,
                'description' => $this->post->description,
            ]);
    }

    public function testGetPost(): void
    {
        $response = $this->getJson(route('posts.show', ['post' => $this->post->id]));

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'created_at',
            ])
            ->assertJsonFragment([
                'id' => $this->post->id,
                'title' => $this->post->title,
                'description' => $this->post->description,
            ]);
    }
}
