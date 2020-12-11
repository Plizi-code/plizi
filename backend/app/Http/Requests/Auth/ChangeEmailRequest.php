<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangeEmailRequest extends FormRequest
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
            'oldEmail' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($user)  {
                    if ($value !== $user->email) {
                        $fail('Поле Старый email не совпадает.');
                    }
                },
            ],
            'newEmail' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value === $user->email) {
                        $fail('Поле Новый email совпадает со старым значением.');
                    }
                },
                'min:8',
                'unique:users,email',
            ],
            'newEmailConfirmation' => ['required', 'email', 'same:newEmail'],
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'oldEmail' => 'старый email',
            'newEmail' => 'новый email',
            'newEmailConfirmation' => 'подтвердите новый email',
        ];
    }
}
