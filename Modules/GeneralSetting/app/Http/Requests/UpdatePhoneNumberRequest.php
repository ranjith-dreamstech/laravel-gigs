<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class UpdatePhoneNumberRequest extends CustomFailedValidation
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'new_phonenumber' => 'required|unique:users,phone_number',
            'current_phonenumber' => 'required',
            'phone_current_password' => 'required',
        ];
    }

    /**
     * Get the validated data with proper types
     *
     * @return array{
     *     phone_current_password: string,
     *     current_phonenumber: string,
     *     new_phonenumber: string
     * }
     */
    public function validatedData(): array
    {
        $validated = $this->validated();

        return [
            'phone_current_password' => (string) $validated['phone_current_password'],
            'current_phonenumber' => (string) $validated['current_phonenumber'],
            'new_phonenumber' => (string) $validated['new_phonenumber'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
