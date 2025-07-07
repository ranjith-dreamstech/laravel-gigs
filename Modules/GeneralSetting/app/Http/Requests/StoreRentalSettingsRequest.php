<?php

namespace Modules\GeneralSetting\Http\Requests;

use App\Library\CustomFailedValidation;

class StoreRentalSettingsRequest extends CustomFailedValidation
{
    private const NULLABLE_BOOLEAN = 'nullable|boolean';
    private const NULLABLE_INTEGER_MIN_0 = 'nullable|integer|min:0';

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
            'minAdvanceReservation' => self::NULLABLE_INTEGER_MIN_0,
            'maxAdvanceReservation' => self::NULLABLE_INTEGER_MIN_0,
            'cancellationBuffer' => self::NULLABLE_INTEGER_MIN_0,
            'rescheduleBuffer' => self::NULLABLE_INTEGER_MIN_0,

            'faq' => self::NULLABLE_BOOLEAN,
            'damages' => self::NULLABLE_BOOLEAN,
            'extraService' => self::NULLABLE_BOOLEAN,
            'booking' => self::NULLABLE_BOOLEAN,
            'enquiries' => self::NULLABLE_BOOLEAN,
            'reservation' => self::NULLABLE_BOOLEAN,
            'seasonalPricing' => self::NULLABLE_BOOLEAN,

            'pricing' => 'nullable|string',
        ];
    }
}
