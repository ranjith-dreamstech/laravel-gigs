<?php

namespace Modules\MenuManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'menu_name' => 'required|string|max:255',
            'menu_type' => 'required|in:header,footer',
            'menu_permalink' => 'required|url|max:255',
            'language' => 'required|integer|exists:translation_languages,id',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'menu_name.required' => __('Menu name is required'),
            'menu_type.required' => __('Menu type is required'),
            'menu_permalink.required' => __('Permalink is required'),
            'menu_permalink.url' => __('Permalink must be a valid URL'),
            'language.required' => __('Language is required'),
        ];
    }
}
