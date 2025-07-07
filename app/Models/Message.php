<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string|int|false|null $size
 */
class Message extends Model
{
    /** @return BelongsTo<User, Message> */
    public function sender(): BelongsTo
    {
        /** @var BelongsTo<User, Message> */
        return $this->belongsTo(User::class, 'sender_id');
    }

    /** @return BelongsTo<User, Message> */
    public function receiver(): BelongsTo
    {
        /** @var BelongsTo<User, Message> */
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
