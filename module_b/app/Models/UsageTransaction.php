<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "token_id",
        "service_name",
        "duration",
        "cost_per_second",
    ];

    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class, 'api_token_id');
    }
}
