<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string|null $first_name
 * @property string|null $profile_description
 * @property string|null $about
 * @property string|null $last_name
 * @property string|null $job_title
 */
class UserDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'parent_id',
        'profile_image',
        'first_name',
        'last_name',
        'mobile_number',
        'gender',
        'dob',
        'address',
        'job_title',
        'language_known',
        'tags',
        'profile_description',
        'skills',
        'about',
        'country_id',
        'state_id',
        'city_id',
        'postal_code',
        'latitude',
        'longitude',
    ];

    /**
     * @return BelongsTo<User, UserDetail>
     */
    public function user(): BelongsTo
    {
        /** @var belongsTo<User, UserDetail> */
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne<User, UserDetail>
     */
    public function userDetail(): HasOne
    {
        /** @var hasOne<User, UserDetail> */
        return $this->hasOne(UserDetail::class);
    }
    /**
     * @return BelongsTo<Country, UserDetail>
     */
    public function country(): BelongsTo
    {
        /** @var belongsTo<Country, UserDetail> */
        return $this->belongsTo(Country::class, 'country_id');
    }
    /**
     * @return BelongsTo<State, UserDetail>
     */
    public function state(): BelongsTo
    {
        /** @var belongsTo<State, UserDetail> */
        return $this->belongsTo(State::class, 'state_id');
    }
    /**
     * @return BelongsTo<City, UserDetail>
     */
    public function city(): BelongsTo
    {
        /** @var belongsTo<City, UserDetail> */
        return $this->belongsTo(City::class, 'city_id');
    }

    public function getProfileImageAttribute(): string
    {
        return uploadedAsset($this->attributes['profile_image'], 'profile');
    }
}
