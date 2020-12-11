<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class RemoveFromFriends extends Request
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
            'userId' => 'required|exists:App\Models\User,id',
        ];
    }

    public function messages()
    {
        return [
            'userId.exists' => 'Данный пользователь не найден'
        ];
    }
}
