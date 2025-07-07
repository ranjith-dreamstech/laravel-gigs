<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class ListCompanyRequest extends CustomFailedValidation
{
    public function authorize(): bool
    {
        return true; // Adjust if using auth
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array<mixed>>
     */
    public function rules(): array
    {
        return [
            'group_id' => 'required|integer',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'group_id.required' => __('admin.general_settings.validation_error'),
        ];
    }
}
