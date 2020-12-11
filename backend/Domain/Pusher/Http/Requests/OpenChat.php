<?php


namespace Domain\Pusher\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class OpenChat extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'userIds' => 'required|array',
            'name' => 'string',
        ];
    }
}
