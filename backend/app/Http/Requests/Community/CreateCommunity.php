<?php


namespace App\Http\Requests\Community;


use App\Models\Community as CommunityModel;
use App\Models\CommunityTheme;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CreateCommunity extends FormRequest
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
            'name' => 'required|string|max:255|unique:App\Models\Community,name',
            'url' => 'string|max:255|unique:App\Models\Community,url',
            'type' => [
                'required',
                Rule::in(array_keys(CommunityModel::getTypeList())),
            ],
            'theme_id' => [
                'required',
                Rule::in(CommunityTheme::getAllChildren()->pluck('id')->toArray()),
            ],
            'privacy' => [
                'required',
                Rule::in(array_keys(CommunityModel::getPrivacyList())),
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'url' => Str::slug($this->request->get('name')),
        ]);
    }
}
