<?php

namespace App\Http\Requests\Blacklist;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class BlacklistStore extends Request
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
            'userId' => [
                'required',
                'exists:users,id',
                Rule::unique('users_blacklisted', 'blacklisted_id')->where('user_id', auth()->user()->id),
                'not_in:' . auth()->id(),
            ],
        ];
    }

    public function messages()
    {
        return [
            'userId.required' => 'Обязательный атрибут',
            'userId.exists' => 'Запись отсутсвует',
            'userId.unique' => 'Уже в черном списке',
            'userId.not_in' => 'Не возможно самого себя добавить в черный список',
        ];
    }
}
