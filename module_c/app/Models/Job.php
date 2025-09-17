<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "user_id",
        "workspace_id",
        "job_id",
        "type",
        "status",
        "started_at",
        "finished_at",
        "local_image_url",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workspace(): BelongsTo {
        return $this->belongsTo(Workspace::class);
    }
}
