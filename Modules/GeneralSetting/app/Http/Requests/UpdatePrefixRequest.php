<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class UpdatePrefixRequest extends CustomFailedValidation
{
    private const PREFIX_RULE = 'required|string|max:20';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'group_id' => 'required|integer',
            'reservation_prefix' => self::PREFIX_RULE,
            'quotation_prefix' => self::PREFIX_RULE,
            'enquiry_prefix' => self::PREFIX_RULE,
            'company_prefix' => self::PREFIX_RULE,
            'inspection_prefix' => self::PREFIX_RULE,
            'report_prefix' => self::PREFIX_RULE,
            'customer_prefix' => self::PREFIX_RULE,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'group_id.required' => __('The group ID is required.'),
            'group_id.integer' => __('The group ID must be an integer.'),
            '*.required' => __('The :attribute field is required.'),
            '*.string' => __('The :attribute must be a string.'),
            '*.max' => __('The :attribute may not be greater than :max characters.'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'reservation_prefix' => 'reservation prefix',
            'quotation_prefix' => 'quotation prefix',
            'enquiry_prefix' => 'enquiry prefix',
            'company_prefix' => 'company prefix',
            'inspection_prefix' => 'inspection prefix',
            'report_prefix' => 'report prefix',
            'customer_prefix' => 'customer prefix',
        ];
    }
}
