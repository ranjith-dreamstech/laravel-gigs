<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class UpdatePaymentStatusRequest extends CustomFailedValidation
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'key' => 'required|string',
            'value' => 'required|in:0,1',
            'group_id' => 'required|integer',
        ];
    }

    /**
     * Get the validated data with proper types
     *
     * @return array{
     *     key: string,
     *     group_id: int,
     *     value: mixed
     * }
     */
    public function validatedData(): array
    {
        $validated = $this->validated();

        return [
            'key' => (string) $validated['key'],
            'group_id' => (int) $validated['group_id'],
            'value' => $validated['value'], // mixed type as per interface
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'value.in' => __('admin.general_settings.status_must_be_0_or_1'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
