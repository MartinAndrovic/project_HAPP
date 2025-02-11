<?php

namespace App\Models;

use App\Notifications\NewMatchNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Filter_Match extends Model
{
    protected $table = 'filter_matches';

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function filter(): BelongsTo
    {
        return $this->belongsTo(Filter::class);
    }
}
