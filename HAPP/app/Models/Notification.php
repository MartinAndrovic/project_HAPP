<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    protected $fillable = ['filter_match_id'];

 public function filter_match(): BelongsTo
 {
     return $this->belongsTo(Filter_Match::class);
 }
}
