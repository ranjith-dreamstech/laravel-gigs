<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string|null $created_date
 * @property string|null $created_at
 */
class Notification extends Model
{
    use SoftDeletes;

    protected $table = 'notifications';
    protected $fillable = ['user_id','related_user_id','subject','content','readed','read_at'];

    /** @return BelongsTo<User, Notification> */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, Notification> */
        return $this->belongsTo(User::class, 'user_id');
    }

    /** @return BelongsTo<User, Notification> */
    public function relatedUser(): BelongsTo
    {
        /** @var BelongsTo<User, Notification> */
        return $this->belongsTo(User::class, 'related_user_id');
    }
}
