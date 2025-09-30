<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        "bill_id",
        "token_id",
        "service_name",
        "duration",
        "price",
        "total_cost",
    ];
    public function token(): BelongsTo {
        return $this->belongsTo(Token::class);
    }
}
