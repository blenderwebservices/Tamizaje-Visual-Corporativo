<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetargetingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'stage',
        'channel',
        'status',
        'scheduled_for',
        'sent_at',
        'message_content',
        'notes',
    ];

    protected $casts = [
        'scheduled_for' => 'date',
        'sent_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
