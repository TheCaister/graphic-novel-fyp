<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Chapter extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasRelationships;
    // public $timestamps = false;
    protected $primaryKey = 'chapter_id';

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

    public function universe(){
        return $this->hasOneThrough(Universe::class, Series::class, 'universe_id', 'series_id', 'chapter_id', 'series_id');

    }

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
        // $this->elements()->delete();
        parent::delete();
    }

    public function elements(): MorphToMany{
        return $this->morphToMany(Element::class, 'elementable', 'elementables', 'elementable_id', 'element_id');
    }

    public function moderators(): MorphToMany
    {
        return $this->morphToMany(User::class, 'moderatable', 'approved_moderators', 'moderatable_id', 'moderator_id', 'chapter_id');
    }

    public function owner()
    {
        return $this->hasOneDeep(User::class, [Series::class, Universe::class], ['series_id', 'universe_id', 'owner_id', 'id']);
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

    public function getSearchFormattedEntry(){
        return [
            'title' => $this->chapter_title,
            'type' => 'chapter',
            'thumbnail' => $this->chapter_thumbnail,
            'link' => route('chapters.show', $this->chapter_id),
        ];
    }

    public function getAssignFormattedEntry($includeDescription = false){
        return [
            'content_id' => $this->chapter_id,
            'content_name' => $this->chapter_title,
            'content_thumbnail' => $this->chapter_thumbnail,
            'description' => $includeDescription ? $this->chapter_notes : '',
        ];
    }

    public function getParentContent(){
        return [
            'content_type' => 'Series',
            'content_id' => $this->series()->first()->series_id
        ];
    }

    public static function getThumbnailCollectionName(){
        return 'chapter_thumbnail';
    }

    public function clearThumbnail(){
        $this->chapter_thumbnail = null;
    }

    public function scopeFilter($query, array $filters)
    {
        // dd($query);
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereRaw("LOWER(chapter_title) LIKE CONCAT('%', LOWER(?), '%')", [$search]);
        });
    }
}
