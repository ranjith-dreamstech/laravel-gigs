<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class UpdateAdminProfileRequest extends CustomFailedValidation
{
    private const NULLABLE_NUMERIC = 'nullable|numeric';

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
            'id' => 'required|exists:users,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->id,
            'phone' => 'required',
            'address_line' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'country' => self::NULLABLE_NUMERIC,
            'state' => self::NULLABLE_NUMERIC,
            'city' => self::NULLABLE_NUMERIC,
        ];
    }
    /**
     * @return array{
     *     id: int,
     *     email: string,
     *     phone: string,
     *     first_name: string,
     *     last_name: string,
     *     profile_photo?: \Illuminate\Http\UploadedFile|null,
     *     address_line?: string|null,
     *     postal_code?: string|null,
     *     country?: int|null,
     *     state?: int|null,
     *     city?: int|null
     * }
     */
    public function validated($key = null, $default = null): array
    {
        return parent::validated($key, $default);
    }
}
