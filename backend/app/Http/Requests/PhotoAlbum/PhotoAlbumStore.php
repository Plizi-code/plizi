<?php

namespace App\Http\Requests\PhotoAlbum;

use Illuminate\Foundation\Http\FormRequest;

class PhotoAlbumStore extends FormRequest
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
            'communityId' => 'sometimes|required|exists:communities,id',
            'title' => 'required|string|min:4|max:100',
            'description' => 'sometimes|nullable|string|max:500',
        ];
    }
}
