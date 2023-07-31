<?php

namespace App\Http\Controllers\Api\Comment;

use App\Http\Controllers\Api\ApiController;
use App\Http\Presenters\CommentPresenter;
use App\Http\Requests\Api\Comment\CommentStoreRequest;
use App\Http\Requests\Api\Comment\CommentUpdateRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class CommentController extends ApiController
{
    /**
     * Get comments for post
     *
     * @param  Post  $post
     * @param  CommentPresenter  $presenter
     * @return JsonResponse
     */
    public function index(Post $post, CommentPresenter $presenter): JsonResponse
    {
        $comments = $post->comments;

        return $this->successResponse($presenter->presentCollection($comments));
    }

    public function store(CommentStoreRequest $request, Post $post, CommentPresenter $presenter): JsonResponse
    {
        $comment = Comment::create([
            'text' => $request->get('text'),
            'parent_id' => $request->get('parent_id'),
            'post_id' => $post->getId()
        ]);

        return $this->successResponse($presenter->present($comment));
    }

    /**
     * Get comment
     *
     * @param  Comment  $comment
     * @param  CommentPresenter  $presenter
     * @return JsonResponse
     */
    public function show(Comment $comment, CommentPresenter $presenter): JsonResponse
    {
        return $this->successResponse($presenter->present($comment));
    }

    public function update(CommentUpdateRequest $request, Comment $comment, CommentPresenter $presenter): JsonResponse
    {
        $comment->update([
            'text' => $request->input('text'),
        ]);

        return $this->successResponse($presenter->present($comment));
    }

    /**
     * delete comment
     *
     * @param  Comment  $comment
     * @return JsonResponse
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return $this->emptyResponse();
    }
}
