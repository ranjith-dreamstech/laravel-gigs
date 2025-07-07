<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class StoreOtpSettingsRequest extends CustomFailedValidation
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
            'otp_type' => 'required',
            'otp_type.*' => 'in:sms,email',
            'otp_digit_limit' => 'required|integer|in:4,5,6',
            'otp_expire_time' => 'required|string|in:2 mins,5 mins,10 mins',
            'login' => 'nullable|boolean',
            'register' => 'nullable|boolean',
        ];
    }

    /**
     * Get the validated data with proper types
     *
     * @return array{otp_type: string, otp_digit_limit: int, otp_expire_time: int, login?: bool, register?: bool}
     */
    public function validatedData(): array
    {
        $validated = $this->validated();

        // Convert otp_expire_time from string to minutes (int)
        $expireTimeMap = [
            '2 mins' => '2 mins',
            '5 mins' => '5 mins',
            '10 mins' => '10 mins',
        ];

        return [
            'otp_type' => is_array($validated['otp_type'])
                ? implode(',', $validated['otp_type'])
                : (string) $validated['otp_type'],
            'otp_digit_limit' => (int) $validated['otp_digit_limit'],
            'otp_expire_time' => $expireTimeMap[$validated['otp_expire_time']],
            'login' => $validated['login'] ?? null,
            'register' => $validated['register'] ?? null,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'otp_type.required' => __('The OTP type field is required.'),
            'otp_type.*.in' => __('Invalid OTP type selected.'),
            'otp_digit_limit.required' => __('The OTP digit limit field is required.'),
            'otp_digit_limit.integer' => __('The OTP digit limit must be a number.'),
            'otp_digit_limit.in' => __('Invalid OTP digit limit selected.'),
            'otp_expire_time.required' => __('The OTP expiry time field is required.'),
            'otp_expire_time.in' => __('Invalid OTP expiry time selected.'),
        ];
    }
}
