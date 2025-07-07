<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class LogoutDeviceRequest extends CustomFailedValidation
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'isAll' => 'required|string|in:true,false',
            'id' => 'nullable|integer',
        ];
    }

    /**
     * Get the validated data with proper types
     *
     * @return array{isAll: string, id?: int}
     */
    public function validatedData(): array
    {
        $validated = $this->validated();

        $data = [
            'isAll' => (string) $validated['isAll'],
        ];

        if (isset($validated['id'])) {
            $data['id'] = (int) $validated['id'];
        }

        return $data;
    }

    public function authorize(): bool
    {
        return true;
    }
}
