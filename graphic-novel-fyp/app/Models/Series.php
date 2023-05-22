<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Series extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'series_id',
        'universe_id',
        'series_title',
        'series_genre',
        'series_summary',
        'series_thumbnail',
        'rating',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'universe_id' => 'integer',
        'rating' => 'float',
    ];

    public function universe(): BelongsTo
    {
        return $this->belongsTo(Universe::class, 'universe_id', 'universe_id');
    }
}