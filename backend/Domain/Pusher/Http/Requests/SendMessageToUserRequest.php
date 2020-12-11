<?php


namespace Domain\Pusher\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class SendMessageToUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'userId' => 'required',
            'body' => 'required|string|max:500',
            'replyOnMessageId' => 'string',
            'forwardFromChatId' => 'string',
            'attachments' => 'array'
        ];
    }
}
