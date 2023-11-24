<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Series extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $primaryKey = 'series_id';
    public $timestamps = false;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('series_thumbnail')->singleFile();
    }

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

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'series_id', 'series_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function elements(): MorphToMany
    {
        return $this->morphToMany(Element::class, 'elementable', 'elementables', 'elementable_id', 'element_id');
    }

    public function moderators(): MorphToMany
    {
        return $this->morphToMany(User::class, 'moderatable', 'approved_moderators', 'moderatable_id', 'moderator_id', 'series_id');
    }

    public function name(): string
    {
        return $this->series_title;
    }

    public function userRatings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_series_rating', 'series_id', 'user_id');
    }
    // Delete all comments associated with the chapter.
    function delete()
    {
        

        // $this->pages()->delete();
        $this->chapters()->delete();
        $this->comments()->delete();
        $this->elements()->delete();

        parent::delete();
    }
}
