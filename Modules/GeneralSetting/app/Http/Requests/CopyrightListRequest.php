<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class CopyrightListRequest extends CustomFailedValidation
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
            'group_id' => 'required|integer',
            'language_id' => 'nullable|integer',
        ];
    }
}
