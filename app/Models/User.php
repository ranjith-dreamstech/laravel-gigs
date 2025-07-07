<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int|null $language_id
 * @property string|null $customer_full_name
 * @property string|null $profile_image
 * @property string|null $language_code
 * @property string|null $language_flag
 * @property string|null $encrypted_id
 * @property string|null $valid_date
 * @property string|null $date_of_issue
 * @property string|null $dob
 * @property string|null $added_on
 * @property int $id
 * @property \Carbon\Carbon|null $last_password_changed_at
 * @property string|null $password
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $mobile_number
 * @property string|null $phone_number
 * @property string|null $phone_number
 * @property int|null $google_auth_enabled
 * @property string|null $full_name
 * @property string|null $username
 * @property int|null $booking_confirmation
 * @property int|null $desktop_notifications
 * @property int|null $email_notifications
 * @property string|null $created_date
 * @property string|null $name
 * @property \App\Models\Message|null $lastMessage
 * @property string|null $lastMessageTime
 * @property string|null $avatar
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    public static string $userSecretKey = 'userId';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'user_type',
        'fcm_token',
        'status',
        'region_id',
        'language_id',
        'last_password_changed_at',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return HasOne<UserDetail, User>
     */
    public function userDetail(): HasOne
    {
        /** @var hasOne<UserDetail, User> */
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    /**
     * @return HasMany<UserDetail, User>
     */
    public function documents(): HasMany
    {
        /** @var hasMany<UserDetail, User> */
        return $this->hasMany(UserDocument::class, 'user_id');
    }

    /**
     * @return HasOne<UserDetail, User>
     */
    public function userDetails(): HasOne
    {
        /** @var hasOne<UserDetail, User> */
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    /**
     * @return HasMany<Gigs, User>
     */
    public function gigs(): HasMany
    {
        /** @var hasMany<Gigs, User> */
        return $this->hasMany(Gigs::class, 'user_id');
    }

    /**
     * @return HasMany<Wishlist, User>
     */
    public function wishlist(): HasMany
    {
        /** @var hasMany<Wishlist, User> */
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    /**
     * @return HasOne<UserDetail, User>
     */
    public function seleterDetail(): HasOne
    {
        /** @var hasOne<UserDetail, User> */
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}
