<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string|null $review_date
 * @property string|null $reply_date
 * @property int|null $gigs_id
 */
class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'gigs_id',
        'user_id',
        'parent_id',
        'comments',
        'ratings',
    ];
    /**
     * @return BelongsTo<User, Review>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, Review> */
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * @return BelongsTo<Gigs, Review>
     */
    public function gig(): BelongsTo
    {
        /** @var BelongsTo<Gigs, Review> */
        return $this->belongsTo(Gigs::class, 'gigs_id');
    }
    /**
     * @return HasMany<Review, Review>
     */
    public function replies(): HasMany
    {
        /** @var HasMany<Review, Review> */
        return $this->hasMany(Review::class, 'parent_id')->orderBy('created_at', 'desc');
    }
}
