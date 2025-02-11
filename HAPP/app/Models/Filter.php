<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age_from',
        'age_to',
        'date_from',
        'date_to',
        'group',
        'gender',
        'regions',
        'districts',
        'categories',
        'activities',

    ];



    public function users_categories(): HasMany
    {
        return $this->hasMany(Users_category::class);
    }

    public function users_regions(): HasMany
    {
        return $this->hasMany(Users_region::class);
    }

    public function filter_matches(): HasMany
    {
        return $this->hasMany(Filter_Match::class, 'filter_1_id')->orWhere('filter_2_id', $this->id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}

