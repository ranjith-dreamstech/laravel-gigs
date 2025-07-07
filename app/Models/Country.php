<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $code
 */
class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = ['name', 'code'];
}
