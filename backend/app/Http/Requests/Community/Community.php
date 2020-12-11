<?php


namespace App\Http\Requests\Community;


use App\Http\Requests\Request;
use App\Models\Community as CommunityModel;
use App\Models\CommunityTheme;
use App\Rules\Website;
use Illuminate\Validation\Rule;

class Community extends Request
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
            'name' => [
                'string',
                'min:2',
                'max:150',
                Rule::unique('communities')->ignore($this->id, 'id'),
            ],
            'description' => 'max:1000',
            'notice' => 'max:150',
            'url' => [
                'max:150',
                function ($attribute, $value, $fail) {
                    if (!$value) {
                        return;
                    }
                    if (CommunityModel::where('url', $value)
                        ->where('id', '!=', $this->id)
                        ->exists()) {
                        $fail('Уже занято');
                    }
                },
            ],
            'website' => ['nullable', new Website()],
            'location' => 'exists:geo_cities,id',
            'privacy' => [
                Rule::in(array_keys(CommunityModel::getPrivacyList())),
            ],
            'type' => [
                Rule::in(array_keys(CommunityModel::getTypeList())),
            ],
            'themeId' => [
                Rule::in(CommunityTheme::getAllChildren()->pluck('id')->toArray()),
            ],
        ];
    }
}
