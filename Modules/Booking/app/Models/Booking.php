<?php

namespace Modules\Booking\Models;

use App\Models\Gigs;
use App\Models\OrderData;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $order_id
 * @property string $transaction_id
 * @property string $gigs_id
 * @property string $booking_date
 * @property string $created_at
 * @property int $booking_status
 * @property int $customer_id
 * @property string $payment_type
 * @property string $cancel_reason
 * @property string $payment_status
 * @property float $final_price
 * @property \App\Models\Gigs|null $gig
 * @property \App\Models\User|null $user
 * @property \App\Models\User|null $seller
 * @property string|null $delivery_by
 * @property string|null $delivery_date
 * @property string|null $booking_status_text
 * @property string|null $payment_status_text
 */
class Booking extends Model
{
    use SoftDeletes;

    // Booking Status Constants
    public static int $new = 1;
    public static int $inprogress = 2;
    public static int $pending = 3;
    public static int $completed = 4;
    public static int $refund = 5;
    public static int $cancelled = 6;
    public static int $refundCompleted = 7;

    public static int $unpaid = 1;
    public static int $paid = 2;

    public static string $reservationSecretKey = 'ReservationId';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'gigs_id',
        'order_id',
        'booking_status',
        'booking_date',
        'delivery_by',
        'quantity',
        'customer_id',
        'category_id',
        'seller_id',
        'extra_service',
        'total_extra_service_price',
        'gigs_price',
        'gigs_total_price',
        'gigs_fast_price',
        'final_price',
        'cancel_date',
        'cancel_by',
        'cancel_reason',
        'created_by',
        'updated_by',
        'transaction_id',
        'payment_status',
        'payment_type',
        'payment_proof',
    ];

    protected $appends = ['encrypted_id'];

    public static function getStatusLabel(int $status): string
    {
        $statuses = [
            self::$new => 'New',
            self::$inprogress => 'In Progress',
            self::$pending => 'Pending',
            self::$completed => 'Completed',
            self::$cancelled => 'Cancelled',
            self::$refund => 'Refund Initiated',
            self::$refundCompleted => 'Refund Completed',
        ];

        return $statuses[$status] ?? 'Unknown';
    }

    public static function getPaymentStatusLabel(int $status): string
    {
        $statuses = [
            self::$unpaid => 'Unpaid',
            self::$paid => 'Paid',
        ];

        return $statuses[$status] ?? 'Unknown';
    }

    public function getEncryptedIdAttribute(): string
    {
        return customEncrypt($this->id, Booking::$reservationSecretKey);
    }

    /**
     * @return BelongsTo<Gigs, self>
     */
    public function gigs(): BelongsTo
    {
        return $this->belongsTo(Gigs::class, 'gigs_id');
    }

    /**
     * @return HasMany<OrderData, self>
     */
    public function orderData(): HasMany
    {
        return $this->hasMany(OrderData::class, 'booking_id', 'id');
    }

    /**
     * @return HasOne<BookingDetail, self>
     */
    public function bookingDetail(): HasOne
    {
        return $this->hasOne(BookingDetail::class, 'booking_id');
    }

    /**
     * @return BelongsTo<User, self>
     */
    public function cancelledUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancel_by');
    }

    /**
     * @return HasOne<BookingUserInfo, self>
     */
    public function userInfo(): HasOne
    {
        return $this->hasOne(BookingUserInfo::class, 'booking_id');
    }

    /**
     * @return BelongsTo<Gigs, self>
     */
    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gigs::class, 'gigs_id');
    }

    /**
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * @return BelongsTo<User, self>
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function getFinalPriceFormattedAttribute(): string
    {
        return formatPrice((float) $this->final_price);
    }
}
