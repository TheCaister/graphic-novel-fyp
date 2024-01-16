<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Chapter extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    // public $timestamps = false;
    protected $primaryKey = 'chapter_id';
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return route('chapters.show', $this->chapter_id);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('chapter_thumbnail')->singleFile();
    }

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
        'scheduled_publish',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'series_id' => 'integer',
        'scheduled_publish' => 'timestamp',
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class, 'series_id', 'series_id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'chapter_id', 'chapter_id');
    }

    function delete()
    {
        $this->pages()->delete();
        $this->elements()->delete();
        parent::delete();
    }

    public function elements(): MorphToMany{
        return $this->morphToMany(Element::class, 'elementable', 'elementables', 'elementable_id', 'element_id');
    }

    public function name(): string
    {
        return $this->chapter_title;
    }

    public function getRecentsFormattedEntry(){
        return [
            'title' => $this->chapter_title,
            'thumbnail' => $this->chapter_thumbnail,
            'link' => route('chapters.show', $this->chapter_id),
        ];
    }
}
