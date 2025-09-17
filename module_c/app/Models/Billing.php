<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "user_id",
        "workspace_id",
        "service_name",
        "cost",
        "duration_ms"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workspace(): BelongsTo {
        return $this->belongsTo(Workspace::class);
    }
}
