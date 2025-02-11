<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Users_category extends Model
{

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function filter(): BelongsTo{

        return $this->belongsTo(Filter::class);
    }

    public function users_activities(): HasMany{

        return $this->hasMany(Users_activity::class);
    }

    use HasFactory;
}
