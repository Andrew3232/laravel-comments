<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Api\ApiController;
use App\Http\Presenters\PostPresenter;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends ApiController
{
    /**
     * Get all posts
     *
     * @param  PostPresenter  $presenter
     * @return JsonResponse
     */
    public function index(PostPresenter $presenter): JsonResponse
    {
        $posts = Post::all();

        return $this->successResponse($presenter->presentCollection($posts));
    }

    /**
     * Get post
     *
     * @param  Post  $post
     * @param  PostPresenter  $presenter
     * @return JsonResponse
     */
    public function show(Post $post, PostPresenter $presenter): JsonResponse
    {
        return $this->successResponse($presenter->present($post));
    }
}
