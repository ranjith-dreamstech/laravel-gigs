<?php

namespace Modules\Page\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Section extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */

    protected $table = 'sections';

    protected $fillable = ['name', 'theme_id', 'status', 'datas', 'title'];
}
