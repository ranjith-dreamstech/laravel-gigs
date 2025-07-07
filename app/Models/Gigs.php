<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Category\Models\Categories;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property float $general_price
 * @property int $days
 * @property int $category_id
 * @property int $user_id
 * @property string $video_platform
 * @property string $video_link
 * @property \App\Models\GigsMeta|null $imageMeta
 */
class Gigs extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'general_price',
        'days',
        'category_id',
        'sub_category_id',
        'no_revisions',
        'tags',
        'description',
        'fast_service_tile',
        'fast_service_price',
        'fast_service_days',
        'fast_dis',
        'buyer',
        'faqs',
        'status',
        'extra_service',
        'video_platform',
        'video_link',
        'is_feature',
        'is_recommend',
        'status',
    ];

    protected $table = 'gigs';

    /** @return HasMany<GigsMeta, Gigs> */
    public function meta(): HasMany
    {
        /** @var HasMany<GigsMeta, Gigs> */
        return $this->hasMany(GigsMeta::class, 'gig_id');
    }

    /**
     * @return BelongsTo<Categories, Gigs>
     */
    public function category(): BelongsTo
    {
        /** @var BelongsTo<Categories, Gigs> */
        return $this->belongsTo(Categories::class, 'category_id');
    }

    /** @return BelongsTo<Categories, Gigs> */
    public function subCategory(): BelongsTo
    {
        /** @var BelongsTo<Categories, Gigs> */
        return $this->belongsTo(Categories::class, 'sub_category_id');  // Assuming sub_category_id references Categories table
    }
    /**
     * @return BelongsTo<User, Gigs>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, Gigs> */
        return $this->belongsTo(User::class, 'user_id');
    }
    /** @return HasMany<GigsMeta, Gigs> */
    public function images(): HasMany
    {
        /** @var HasMany<GigsMeta, Gigs> */
        return $this->hasMany(GigsMeta::class, 'gig_id')->where('key', 'gigs_image');
    }

    /** @return HasOne<GigsMeta, Gigs> */
    public function imageMeta(): HasOne
    {
        /** @var HasOne<GigsMeta, Gigs> */
        return $this->hasOne(GigsMeta::class, 'gig_id')->where('key', 'gigs_image');
    }

    /** @return HasMany<Review, Gigs> */
    public function reviews(): HasMany
    {
        /** @var HasMany<Review, Gigs> */
        return $this->hasMany(Review::class, 'gigs_id');
    }
}
