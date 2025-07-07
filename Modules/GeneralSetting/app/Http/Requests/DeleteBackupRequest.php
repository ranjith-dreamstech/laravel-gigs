<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class DeleteBackupRequest extends CustomFailedValidation
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:dbbackups,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
