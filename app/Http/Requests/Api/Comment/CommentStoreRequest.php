<?php

namespace App\Http\Requests\Api\Comment;

use App\Http\Requests\Api\ApiFormRequest;

class CommentStoreRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'text' => 'required|string',
            'parent_id' => 'integer|nullable',
        ];
    }
}
