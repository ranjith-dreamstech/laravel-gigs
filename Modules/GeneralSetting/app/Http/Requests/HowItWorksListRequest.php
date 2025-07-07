<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class HowItWorksListRequest extends CustomFailedValidation
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
     * Get the validated data with proper types
     *
     * @return array{group_id: int, language_id?: int}
     */
    public function validatedData(): array
    {
        $validated = $this->validated();

        $data = [
            'group_id' => (int) $validated['group_id'],
        ];

        if (isset($validated['language_id'])) {
            $data['language_id'] = (int) $validated['language_id'];
        }

        return $data;
    }
}
