<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "workspace_id",
        "limit",
        "current_quota",
        "remaining_days",
    ];
}
