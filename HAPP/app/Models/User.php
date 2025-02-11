<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'oauth_id',
        'is_admin',
        'age',
        'gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function profile_images(): HasMany
    {
        return $this->hasMany(Profile_image::class);
    }

    public function users_activities(): HasMany
    {
        return $this->hasMany(Users_activity::class);
    }

    public function users_categories(): HasMany
    {
        return $this->hasMany(Users_category::class);
    }

    public function users_regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    public function users_districts(): HasMany
    {
        return  $this->hasMany(Users_district::class);
    }

    public function filters(): HasMany
    {
        return $this->hasMany(Filter::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'to')->orWhere('from', $this->id);
    }

    public function isAdmin():bool {
        return $this->is_admin;
    }



}
