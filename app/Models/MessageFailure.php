<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageFailure extends BaseModel
{
    protected $table = 'message_failures';

    protected $guarded = [];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
