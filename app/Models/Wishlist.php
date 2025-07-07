<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int|null $service_id
 * @property int|null $user_id
 * @property int $id
 */
class Wishlist extends Model
{
    protected $fillable = ['user_id','service_id'];

    /**
     * @return BelongsTo<Gigs, Wishlist>
     */
    public function gigs(): BelongsTo
    {
        /** @var BelongsTo<Gigs, Wishlist> */
        return $this->belongsTo(Gigs::class, 'service_id');
    }

    /**
     * @return BelongsTo<User, Wishlist>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, Wishlist> */
        return $this->belongsTo(User::class, 'user_id');
    }
}
