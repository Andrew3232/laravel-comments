<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Contracts\PresenterCollectionInterface;
use App\Models\Post;
use Illuminate\Support\Collection;

final class PostWithCommentsPresenter implements PresenterCollectionInterface
{
    public function __construct(
        private CommentPresenter $commentPresenter
    ){}

    public function present(Post $post): array
    {
        return [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription(),
            'created_at' => $post->getCreatedAt()->longRelativeToNowDiffForHumans(),
            'comments' => $this->commentPresenter->presentCollection($post->comments),
        ];
    }

    public function presentCollection(Collection $collection): array
    {
        return $collection->map(
            function (Post $post) {
                return $this->present($post);
            }
        )->all();
    }
}
