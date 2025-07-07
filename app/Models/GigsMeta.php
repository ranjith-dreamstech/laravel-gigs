<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GigsMeta extends Model
{
    protected $fillable = [
        'gig_id',
        'key',
        'value',
    ];

    protected $appends = [
        'gigs_image_url',
    ];

    /** @return string */
    public function getGigsImageUrlAttribute(): string
    {
        if ($this->key === 'gigs_image' && $this->value) {
            $images = json_decode($this->value, true);

            if (is_array($images) && count($images)) {
                return uploadedAsset($images[0], 'default');
            }
        }

        return '';
    }
}
