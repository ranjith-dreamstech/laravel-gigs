<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read User $user
 */
class UserDocument extends Model
{
    protected $fillable = [
        'user_id',
        'document',
    ];
    /**
     * @return BelongsTo<User, UserDocument>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, UserDocument> */
        return $this->belongsTo(User::class, 'id');
    }
}
