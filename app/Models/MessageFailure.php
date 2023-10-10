<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageFailure extends Model
{
    protected $table = 'hypercemail_message_failures';

    protected $guarded = [];

    /**
     * @return BelongsTo
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
