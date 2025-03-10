<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{

    protected $fillable = [
        'name'

    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function users_activity(): HasMany
    {
        return $this->hasMany(Users_activity::class);
    }

    use HasFactory;
}
