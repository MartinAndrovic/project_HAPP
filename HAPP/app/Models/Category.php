<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{

    protected $fillable = [
        'name'

    ];
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function users_categories(): HasMany
    {
        return $this->hasMany(Users_category::class);
    }

    use HasFactory;
}
