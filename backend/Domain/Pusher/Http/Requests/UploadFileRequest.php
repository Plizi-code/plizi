<?php


namespace Domain\Pusher\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'files.*' => ''
        ];
    }
}
