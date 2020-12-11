<?php


namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PostBody implements Rule
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
        /**
         * Empty body for image only
         */
        if (!$value) {
            $attachmentIds = request()->get('attachmentIds');
            if ($attachmentIds) {
                return true;
            }
        }

        $validator = Validator::make(request()->all(), [
            'body' => 'required|string|max:10050',
        ]);
        return $validator->passes();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Заполните поле';
    }
}
