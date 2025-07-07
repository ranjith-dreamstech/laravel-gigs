<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class UpdateThemeSettingsRequest extends CustomFailedValidation
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
            'default_theme' => 'required',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'group_id.required' => __('admin.general_settings.group_id_required'),
            'default_theme.required' => __('admin.general_settings.default_theme_required'),
        ];
    }
}
