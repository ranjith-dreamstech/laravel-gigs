<?php

namespace Modules\GeneralSetting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string|null $image
 * @property string $slug
 * @property string $category
 * @property string $title
 * @property string $description
 * @property string $popular
 * @property string $tags
 * @property string $seo_title
 * @property string $seo_description
 * @property int|null $parent_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class BlogPost extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'slug', 'image', 'category', 'description', 'popular', 'status', 'tags', 'seo_title', 'seo_description', 'language_id', 'parent_id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by'];

    /**
     * Generate the file URL for the client image.
     *
     * @param string $file
     *
     * @return string
     */
    public function file(string $file): string
    {
        return url('storage/blogs') . '/' . $file;
    }

    /**
     * Get the category that owns the blog post.
     *
     * @return BelongsTo<BlogCategory, BlogPost>
     */
    public function category(): BelongsTo
    {
        /** @var BelongsTo<BlogCategory, BlogPost> */
        return $this->belongsTo(BlogCategory::class, 'category');
    }

    /**
     * Get the tags for the blog post.
     *
     * @return BelongsToMany<BlogTag, BlogPost>
     */
    public function tags(): BelongsToMany
    {
        /** @var BelongsToMany<BlogTag, BlogPost> */
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
    }
}
