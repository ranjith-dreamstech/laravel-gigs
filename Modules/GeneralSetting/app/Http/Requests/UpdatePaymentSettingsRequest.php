<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class UpdatePaymentSettingsRequest extends CustomFailedValidation
{
    private const SOMETIMES_STRING = 'sometimes|string';
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'group_id' => 'required|integer',
            'paypal_key' => self::SOMETIMES_STRING,
            'paypal_secret' => self::SOMETIMES_STRING,
            'stripe_key' => self::SOMETIMES_STRING,
            'stripe_secret' => self::SOMETIMES_STRING,
        ];
    }

    /**
     * Get the validated data with proper types
     *
     * @return array{
     *     group_id: int,
     *     _token?: string,
     *     paypal_key?: string,
     *     paypal_secret?: string,
     *     stripe_key?: string,
     *     stripe_secret?: string
     * }
     */
    public function validatedData(): array
    {
        $validated = $this->validated();

        $data = [
            'group_id' => (int) $validated['group_id'],
        ];

        // Add optional fields only if they exist in the request

        if ($this->has('paypal_key')) {
            $data['paypal_key'] = (string) $validated['paypal_key'];
        }
        if ($this->has('paypal_secret')) {
            $data['paypal_secret'] = (string) $validated['paypal_secret'];
        }
        if ($this->has('stripe_key')) {
            $data['stripe_key'] = (string) $validated['stripe_key'];
        }
        if ($this->has('stripe_secret')) {
            $data['stripe_secret'] = (string) $validated['stripe_secret'];
        }

        return $data;
    }

    public function authorize(): bool
    {
        return true;
    }
}
