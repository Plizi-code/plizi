<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
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
        $user = $this->user();

        return [
            'oldPassword' => ['required',
                function ($attribute, $value, $fail) use ($user)  {
                    if (!(Hash::check($value, $user->password))) {
                        $fail('Поле Старый пароль не совпадает.');
                    }
                },
            ],
            'newPassword' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (Hash::check($value, $user->password)) {
                        $fail('Поле Новый пароль совпадает со старым значением.');
                    }
                },
                'min:8',
            ],
            'newPasswordConfirmation' => ['required', 'same:newPassword'],
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'oldPassword' => 'старый пароль',
            'newPassword' => 'новый пароль',
            'newPasswordConfirmation' => 'подтвердите новый пароль',
        ];
    }
}
