<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "username",
        "workspace_title",
        "api_token_name",
        "usage_duration_in_ms",
        "usage_started_at",
        "service_name",
        "service_cost_per_ms"
    ];
}
