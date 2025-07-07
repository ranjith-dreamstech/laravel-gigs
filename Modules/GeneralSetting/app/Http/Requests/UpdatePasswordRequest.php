<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends CustomFailedValidation
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
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:new_password',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.required' => __('admin.general_settings.enter_current_password'),
            'new_password.required' => __('admin.general_settings.enter_new_password'),
            'new_password.min' => __('admin.general_settings.password_character'),
            'confirm_password.required' => __('admin.general_settings.enter_confirm_password'),
            'confirm_password.same' => __('admin.general_settings.confirm_password_match'),
        ];
    }

    /**
     * Get the validated data with proper types
     *
     * @return array{current_password: string, new_password: string}
     */
    public function validatedData(): array
    {
        $validated = $this->validated();

        return [
            'current_password' => (string) $validated['current_password'],
            'new_password' => (string) $validated['new_password'],
        ];
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $user = current_user();

            if ($user && ! Hash::check($this->current_password, $user->getAuthPassword())) {
                $validator->errors()->add('current_password', __('admin.general_settings.current_password_incorrect'));
            }
        });
    }
}
