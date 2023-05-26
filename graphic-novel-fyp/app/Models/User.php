<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_admin',
        'username',
        'password',
        'email',
        'date_of_birth',
        'is_banned',
        'bio',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_admin' => 'boolean',
        'date_of_birth' => 'date',
        'is_banned' => 'boolean',
        'created_at' => 'timestamp',
    ];

    public function universes(): HasMany
    {
        return $this->hasMany(Universe::class, 'owner_id', 'id');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followee_id');
    }

    public function elements(): HasMany
    {
        return $this->hasMany(Element::class, 'owner_id', 'id');
    }

    // Retrieves the users that follow this user
    public function followees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'followee_id', 'follower_id');
    }
}
