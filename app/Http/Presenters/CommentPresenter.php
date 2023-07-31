<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Contracts\PresenterCollectionInterface;
use App\Models\Comment;
use Illuminate\Support\Collection;

final class CommentPresenter implements PresenterCollectionInterface
{
    public function present(Comment $comment): array
    {
        return [
            'id' => $comment->getId(),
            'text' => $comment->getText(),
            'parentId' =>$comment->getParentId(),
            'created_at' => $comment->getCreatedAt()->longRelativeToNowDiffForHumans(),
            'replies' => $this->presentCollection($comment->replies),
        ];
    }

    public function presentCollection(Collection $collection): array
    {
        return $collection->map(
            function (Comment $comment) {
                return $this->present($comment);
            }
        )->all();
    }
}
