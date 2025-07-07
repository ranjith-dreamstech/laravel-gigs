<?php

namespace Modules\Communication\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_id', 'priority', 'user_id', 'subject', 'description',
        'user_type', 'status', 'reply_description', 'attachment',
        'assignee_id', 'created_by', 'updated_by',
    ];

    protected array $dates = ['deleted_at'];

    /**
     * Get the user that owns the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id')->with('userDetail');
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'subject', 'id');
    }
    public function ticketHistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TicketHistory::class, 'ticket_id');
    }
}
