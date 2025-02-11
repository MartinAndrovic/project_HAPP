<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{

    protected $fillable = [
        'name'

    ];

    public function users_districts(): HasMany
    {
        return $this->hasMany(Users_districts::class);
    }

    public function region(): BelongsTo
    {
        return  $this->belongsTo(Region::class);
    }


    use HasFactory;
}
