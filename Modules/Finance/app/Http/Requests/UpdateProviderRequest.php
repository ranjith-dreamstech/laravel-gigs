<?php

namespace Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProviderRequest extends FormRequest
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
            'provider_id' => 'required|exists:users,id',
            'provider_amount' => 'required|numeric|min:0',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }
}
