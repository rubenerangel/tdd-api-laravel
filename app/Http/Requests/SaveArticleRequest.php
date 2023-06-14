<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SaveArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.title' => ['required', 'min:4'],
            'data.attributes.slug' => [
                'required',
                Rule::unique('articles', 'slug')->ignore($this->route('article')),
            ],
            'data.attributes.content' => ['required',],
        ];
    }

    public function validated($key = "data.attributes", $default = null)
    {
        return parent::validated()['data']['attributes'];
    }
}
