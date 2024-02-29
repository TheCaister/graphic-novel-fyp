<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Universe extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    
    protected $primaryKey = 'universe_id';

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('universe_thumbnail')->singleFile();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'universe_id',
        'owner_id',
        'universe_name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'owner_id' => 'integer',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function series(): HasMany
    {
        return $this->hasMany(Series::class, 'universe_id', 'universe_id');
    }

    public function name(): string
    {
        return $this->universe_name;
    }

    public function elements(): MorphToMany
    {
        return $this->morphToMany(Element::class, 'elementable', 'elementables', 'elementable_id', 'element_id');
    }

    public function moderators(): MorphToMany
    {
        return $this->morphToMany(User::class, 'moderatable', 'approved_moderators', 'moderatable_id', 'moderator_id', 'universe_id');
    }

    public function getRecentsFormattedEntry(){
        return [
            'title' => $this->universe_name,
            'thumbnail' => $this->getFirstMediaUrl('universe_thumbnail'),
            'link' => route('universes.show', $this->universe_id),
        ];
    }

    public function getSearchFormattedEntry(){
        return [
            'title' => $this->universe_name,
            'type' => 'universe',
            'thumbnail' => $this->getFirstMediaUrl('universe_thumbnail'),
            'link' => route('universes.show', $this->universe_id),
        ];
    }

    public static function getThumbnailCollectionName(){
        return 'universe_thumbnail';
    }

    function delete()
    {
        $this->elements()->delete();
        $this->series()->delete();

        parent::delete();
    }

    public function clearThumbnail(){
        return;
    }

    // WIP
    // WILL USE SIMILAR LOGIC FOR SERIES, CHAPTERS, PAGES
    public function scopeFilter($query, array $filters)
    {
        // dd($query);
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereRaw("LOWER(universe_name) LIKE CONCAT('%', LOWER(?), '%')", [$search]);
        });
    }

    public function scopeAuthors($query, $user)
    {
        $query->where('owner_id', $user->id);
    }

    public function scopeIncludedElements($query, $elementType)
    {
        $query->whereHas('elements', function ($query) use ($elementType) {
            $query->where('derived_element_type', $elementType);
        });
    }
}
