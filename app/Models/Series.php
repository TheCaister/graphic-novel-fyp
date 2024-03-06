<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Series extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $primaryKey = 'series_id';
    // public $timestamps = false;

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

    public function owner(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Universe::class, 'universe_id', 'id', 'universe_id', 'owner_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'series_id', 'series_id');
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

    public function getRecentsFormattedEntry()
    {
        return [
            'title' => $this->series_title,
            'thumbnail' => $this->series_thumbnail,
            'link' => route('series.show', $this->series_id),
        ];
    }

    public function getSearchFormattedEntry(){
        return [
            'title' => $this->series_title,
            'type' => 'series',
            'thumbnail' => $this->series_thumbnail,
            'link' => route('series.show', $this->series_id),
        ];
    }

    public function getAssignFormattedEntry($includeDescription = false){
        return [
            'content_id' => $this->series_id,
            'content_name' => $this->series_title,
            'content_thumbnail' => $this->series_thumbnail,
            'description' => $includeDescription ? $this->series_summary : '',
        ];
    }

    public static function getThumbnailCollectionName()
    {
        return 'series_thumbnail';
    }

    public function getParentContent(){
        return [
            'content_type' => 'Universe',
            'content_id' => $this->universe()->first()->universe_id
        ];
    }

    function delete()
    {
        $this->chapters()->delete();
        // $this->elements()->delete();

        parent::delete();
    }

    public function clearThumbnail(){
        $this->series_thumbnail = null;

    }

    public function scopeFilter($query, array $filters)
    {
        // dd($query);
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereRaw("LOWER(series_title) LIKE CONCAT('%', LOWER(?), '%')", [$search]);
        });
    }
}
