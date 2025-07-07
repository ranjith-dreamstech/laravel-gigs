<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class UpdateEmailRequest extends CustomFailedValidation
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'new_email' => 'required|email|unique:users,email',
            'current_email' => 'required|email',
            'email_current_password' => 'required|string',
        ];
    }

    /**
     * Get the validated data with proper types
     *
     * @return array{
     *     email_current_password: string,
     *     current_email: string,
     *     new_email: string
     * }
     */
    public function validatedData(): array
    {
        $validated = $this->validated();

        return [
            'email_current_password' => (string) $validated['email_current_password'],
            'current_email' => (string) $validated['current_email'],
            'new_email' => (string) $validated['new_email'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
