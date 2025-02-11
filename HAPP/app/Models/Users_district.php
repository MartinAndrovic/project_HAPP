<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use function Symfony\Component\Translation\t;

class Users_district extends Model
{
    public function district(): BelongsTo
    {
        return  $this->belongsTo(District::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users_region(): BelongsTo{

        return $this->belongsTo(UsersRegion::class);
    }


    use HasFactory;
}
