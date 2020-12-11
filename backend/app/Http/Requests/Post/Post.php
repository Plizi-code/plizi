<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\Request;
use App\Rules\PostBody;

class Post extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'attachmentIds' => 'array',
            'body' => [
                new PostBody()
            ],
        ];
    }
}
