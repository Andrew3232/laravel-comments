<?php


use App\Models\Comment;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private string $api_url_index;
    private string $api_url_show;
    private string $api_url_update;
    private string $api_url_store;
    private string $api_url_delete;
    private string $updatedText;

    private mixed $post;
    private mixed $testComment;


    public function setUp(): void
    {
        parent::setUp();

        $this->post = Post::create([
            'title' => 'test Title',
            'description' => 'Test description',
        ]);

        $this->testComment = Comment::factory()->create(['post_id' => $this->post->id]);
        $this->updatedText = 'new text for comment';

        $this->api_url_index = route('posts.comments.index', ['post' => $this->post->id]);
        $this->api_url_store = route('posts.comments.store', ['post' => $this->post->id]);
        $this->api_url_show = route('comments.show', ['comment' => $this->testComment->id]);
        $this->api_url_update = route('comments.update', ['comment' => $this->testComment->id]);
        $this->api_url_delete = route('comments.destroy', ['comment' => $this->testComment->id]);
    }

    public function testGetCommentsForPost(): void
    {
        $response = $this->getJson($this->api_url_index);

        $response->assertOk()
            ->assertJsonStructure([
                "*" => [
                    'id',
                    'text',
                    'parentId',
                    'created_at',
                    'replies'
                ]
            ])
            ->assertJsonFragment([
                'id' => $this->testComment->id,
                'text' => $this->testComment->text,
            ]);
    }

    public function testGetComment(): void
    {
        $response = $this->getJson($this->api_url_show);

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'text',
                'parentId',
                'created_at',
                'replies'
            ])
            ->assertJsonFragment([
                'id' => $this->testComment->id,
                'text' => $this->testComment->text,
            ]);
    }

    public function testStoreCommentToPost(): void
    {
        $data = ['text' => 'my text'];
        $response = $this->postJson($this->api_url_store, $data);

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'text',
                'parentId',
                'created_at',
                'replies'
            ])
            ->assertJsonFragment([
                'text' => $data['text'],
            ]);

        $this->assertDatabaseHas('comments', [
            'text' => $data['text'],
            'post_id' => $this->post->id,
        ]);
    }

    public function testStoreReplyToComment(): void
    {
        $data = [
            'text' => 'my text',
            'parent_id' => $this->testComment->id
        ];
        $this->assertDatabaseMissing('comments', [
            'text' => $data['text'],
            'parent_id' => $data['parent_id'],
            'post_id' => $this->post->id,
        ]);
        $response = $this->postJson($this->api_url_store, $data);

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'text',
                'parentId',
                'created_at',
                'replies'
            ])
            ->assertJsonFragment([
                'text' => $data['text'],
                'parentId' => $data['parent_id'],
            ]);

        $this->assertDatabaseHas('comments', [
            'text' => $data['text'],
            'parent_id' => $data['parent_id'],
            'post_id' => $this->post->id,
        ]);
    }

    public function testRequireTextForComment(): void
    {
        $data = ['text' => ''];
        $response = $this->postJson($this->api_url_store, $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'text'
            ]);

        $response = $this->putJson($this->api_url_update, $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([
                'text'
            ]);
    }

    public function testUpdateComment(): void
    {
        $data = ['text' => $this->updatedText];

        $this->assertDatabaseMissing('comments', [
            'id' => $this->testComment->id,
            'text' => $this->testComment->updatedText
        ]);

        $response = $this->putJson($this->api_url_update, $data);

        $response->assertOk()
            ->assertJsonFragment([
                'text' => $this->updatedText
            ]);

        $this->assertDatabaseHas('comments', [
            'id' => $this->testComment->id,
            'text' => $this->updatedText
        ]);
    }

    public function testDeleteComment(): void
    {
        $this->assertDatabaseHas('comments', ['id' => $this->testComment->id]);
        $response = $this->deleteJson($this->api_url_delete);

        $response->assertNoContent();
        $this->assertDatabaseMissing('comments', ['id' => $this->testComment->id]);
    }
}
