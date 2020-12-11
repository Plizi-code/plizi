<?php


namespace App\Http\Requests\Community;

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
            'id' => 'required',
            'file' => 'required|image'
        ];
    }
}
