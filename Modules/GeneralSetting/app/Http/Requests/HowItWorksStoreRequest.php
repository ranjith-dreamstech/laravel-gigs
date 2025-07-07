<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class HowItWorksStoreRequest extends CustomFailedValidation
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
            'language' => 'required|integer',
            'howitwork_description' => 'required|string|min:10',
        ];
    }
    /**
     * @return array{
     *     group_id: int,
     *     language: int,
     *     howitwork_description: string
     * }
     */
    public function validated($key = null, $default = null): array
    {
        return parent::validated($key, $default);
    }
}
