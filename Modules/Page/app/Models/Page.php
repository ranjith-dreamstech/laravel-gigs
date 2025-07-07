<?php

namespace Modules\Page\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    /**
     * The theme ID associated with the page.
     *
     * @var int|null
     */
    public $themeId;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'theme_id',
        'page_title',
        'slug',
        'page_content',
        'seo_tag',
        'seo_title',
        'seo_description',
        'keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'language_id',
        'status',
        'created_at',
    ];
}
