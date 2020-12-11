<?php

namespace App\Http\Requests\Video;

use Illuminate\Foundation\Http\FormRequest;

class VideoStore extends FormRequest
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
            'link' => [
                "required",
                "url",
                function ($attribute, $value, $fail) {
                    if (!preg_match_all("/https?:\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|www.youtube(?:-nocookie)?\.com\S*?[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:[\'\"][^<>]*>|<\/a>))[?=&+%\w.-]*/i", $value)) {
                       $fail('Значение поле Ссылка на видео не является корректной ссылкой на сервис youtube');
                    }
                },
            ],
            'creatableby_id' => [
                'sometimes',
                'nullable',
            ],
            'creatableby_type' => [
                'sometimes',
                'nullable',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'link' => 'Ссылка на видео',
        ];
    }
}
