<?php

namespace Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int|null $featured
 * @property string $status
 * @property string|null $image
 * @property string|null $icon
 * @property int|null $parent_id
 * @property int|null $language_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read Categories|null $parentCategory
 */
class Categories extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'parent_id',
        'source_type',
        'image',
        'icon',
        'status',
        'description',
        'featured',
        'slug',
        'language_id',
        'parent_language_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * @return HasMany<\Modules\Category\Models\Categories, \Modules\Category\Models\Categories>
     */
    public function subCategories(): HasMany
    {
        /** @var HasMany<\Modules\Category\Models\Categories, \Modules\Category\Models\Categories> */
        return $this->hasMany(Categories::class, 'parent_id');
    }

    /**
     * @return BelongsTo<\Modules\Category\Models\Categories, \Modules\Category\Models\Categories>
     */
    public function parentCategory(): BelongsTo
    {
        /** @var BelongsTo<\Modules\Category\Models\Categories, \Modules\Category\Models\Categories> */
        return $this->belongsTo(Categories::class, 'parent_id');
    }
}
