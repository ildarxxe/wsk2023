<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "user_id",
        "title",
        "description",
    ];

    public function tokens(): HasMany
    {
        return $this->hasMany(ApiToken::class);
    }
}
