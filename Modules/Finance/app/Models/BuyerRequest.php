<?php

namespace Modules\Finance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BuyerRequest extends Model
{
    use SoftDeletes;

    // Add these properties to avoid property not found errors
    /**
     * @var int
     */
    public $status;

    /**
     * @var string|null
     */
    public $paymentProofPath;

    protected $fillable = [
        'provider_id',
        'payment_id',
        'amount',
        'status',
    ];

    /**
     * Get the provider associated with the buyer request.
     *
     * @return mixed
     */
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
