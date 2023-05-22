<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follower extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'follower_id',
        'followee_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'follower_id' => 'integer',
        'followee_id' => 'integer',
    ];

    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function followee(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
