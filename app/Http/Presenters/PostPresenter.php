<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Contracts\PresenterCollectionInterface;
use App\Models\Post;
use Illuminate\Support\Collection;

final class PostPresenter implements PresenterCollectionInterface
{
    public function present(Post $post): array
    {
        return [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription(),
            'created_at' => $post->getCreatedAt()->longRelativeToNowDiffForHumans(),
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
