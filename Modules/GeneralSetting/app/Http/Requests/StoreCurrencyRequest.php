<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;
use Illuminate\Validation\Rule;

class StoreCurrencyRequest extends CustomFailedValidation
{
    /**
     * @return array<string, string|array<int, \Illuminate\Contracts\Validation\Rule|string>>
     */
    public function rules(): array
    {
        return [
            'currency_name' => [
                'required',
                Rule::unique('currencies', 'currency_name')
                    ->ignore($this->id)
                    ->whereNull('deleted_at'),
            ],
            'code' => 'required',
            'symbol' => 'required',
        ];
    }
    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'currency_name.required' => __('admin.general_settings.enter_currency_name'),
            'currency_name.unique' => __('admin.general_settings.currency_name_unique'),
            'code.required' => __('admin.general_settings.enter_currency_code'),
            'symbol.required' => __('admin.general_settings.enter_currency_symbol'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
