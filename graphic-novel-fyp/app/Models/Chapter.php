<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    use HasFactory;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chapter_id',
        'series_id',
        'chapter_number',
        'chapter_title',
        'chapter_thumbnail',
        'chapter_notes',
        'comments_enabled',
        'scheduled_publish',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'series_id' => 'integer',
        'comments_enabled' => 'boolean',
        'scheduled_publish' => 'timestamp',
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class, 'series_id', 'series_id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
