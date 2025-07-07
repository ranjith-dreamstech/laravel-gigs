<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceRequest extends FormRequest
{
    private const REQUIRED_NUMERIC = 'required|numeric';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'provider_id' => 'required|exists:users,id',
            'total_bookings' => 'required|integer',
            'total_earnings' => self::REQUIRED_NUMERIC,
            'admin_earnings' => self::REQUIRED_NUMERIC,
            'provider_pay_due' => self::REQUIRED_NUMERIC,
            'entered_amount' => [
                'required',
                'numeric',
                function ($value, $fail) {
                    if ($this->provider_pay_due > 0 && $value > $this->provider_pay_due) {
                        $fail('The entered amount must not be greater than the provider\'s pay due (' . $this->provider_pay_due . ').');
                    }
                },
            ],
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }
}
