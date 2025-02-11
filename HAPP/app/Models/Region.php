<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{

    protected $fillable = [
        'name'

    ];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    public function users_regions(): HasMany
    {
        return $this->hasMany(Users_region::class);
    }


    use HasFactory;
}

