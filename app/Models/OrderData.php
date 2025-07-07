<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Booking\Models\Booking;

/**
 * @property string|null $updated_date
 * @property string|null $file_type
 * @property int|string|null $created_by
 * @property int|string|null $uploaded_by
 */
class OrderData extends Model
{
    protected $fillable = [
        'uploaded_by',
        'gigs_id',
        'booking_id',
        'buyer_id',
        'data',
        'file_type',
    ];

    protected $appends = ['file_data'];
    /** @return BelongsTo<Gigs, OrderData> */
    public function gigs(): BelongsTo
    {
        /** @var BelongsTo<Gigs, OrderData> */
        return $this->belongsTo(Gigs::class, 'gigs_id');
    }

    /** @return BelongsTo<Booking, OrderData> */
    public function booking(): BelongsTo
    {
        /** @var BelongsTo<Booking, OrderData> */
        return $this->belongsTo(Booking::class, 'booking_id');
    }
    /** @return string */
    public function getDataAttribute(): string
    {
        /** @var string */
        return uploadedAsset($this->attributes['data'], 'profile');
    }
    /** @return array  <string, mixed> */
    public function getFileDataAttribute(): array
    {
        /** @var array  <string, mixed> */
        return uploadedAssetDetails($this->attributes['data'], 'default');
    }
}
