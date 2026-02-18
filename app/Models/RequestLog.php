<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestLog extends Model
{
    protected $fillable = [
        'request_id',
        'old_status',
        'new_status',
        'changed_by',
    ];

    public function repairRequest(): BelongsTo
    {
        return $this->belongsTo(RepairRequest::class, 'request_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
