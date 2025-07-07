<?php

namespace Modules\Finance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string|null $created_at
 */
class PayoutHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'user_id',
        'reference_id',
        'total_bookings',
        'total_earnings',
        'admin_earnings',
        'pay_due',
        'process_amount',
        'remaining_amount',
        'payment_proof',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the user associated with the payout history.
     *
     * @return BelongsTo<User, PayoutHistory>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, PayoutHistory> */
        return $this->belongsTo(User::class);
    }
}
