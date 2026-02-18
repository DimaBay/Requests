<?php

namespace App\Models;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RepairRequest extends Model
{
    protected $table = 'requests';

    protected $fillable = [
        'client_name',
        'phone',
        'address',
        'problem_text',
        'status',
        'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'status' => RequestStatus::class,
        ];
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(RequestLog::class, 'request_id');
    }
}
