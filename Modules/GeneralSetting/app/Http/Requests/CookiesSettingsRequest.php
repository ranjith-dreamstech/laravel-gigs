<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class CookiesSettingsRequest extends CustomFailedValidation
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'group_id' => 'required|integer',
            'language_id' => 'nullable|integer',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'group_id.required' => __('The group ID is required.'),
            'group_id.integer' => __('The group ID must be an integer.'),
            'language_id.integer' => __('The language ID must be an integer.'),
        ];
    }
}
