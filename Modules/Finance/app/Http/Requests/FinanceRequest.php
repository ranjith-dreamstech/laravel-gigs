<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinanceRequest extends FormRequest
{
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
            'bookingid' => 'required|exists:bookings,id',
            'payment_proof' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ];
    }
}
