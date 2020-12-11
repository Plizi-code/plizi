<?php


namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;

class Website implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !!preg_match('#^(https?://)?([a-z0-9\-]+\.)+[a-z]{2,6}/?$#i', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Неверный формат адреса сайта';
    }
}
