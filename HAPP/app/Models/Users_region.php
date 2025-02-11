<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Users_region extends Model
{



    public function filter(): BelongsTo{

        return $this->belongsTo(Filter::class);
    }


    public function users_districts(): HasMany{

        return $this->hasMany(Users_district::class);
    }

    use HasFactory;
}
