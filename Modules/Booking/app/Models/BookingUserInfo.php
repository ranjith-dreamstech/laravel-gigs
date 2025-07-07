<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingUserInfo extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'booking_id',
        'first_name',
        'last_name',
        'category_id',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'pincode',
        'email',
    ];

    // protected static function newFactory(): BookingUserInfoFactory
    // {
    //     // return BookingUserInfoFactory::new();
    // }
}
