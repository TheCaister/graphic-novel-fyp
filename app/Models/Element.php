<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Element extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('element_thumbnail')->singleFile();
    }

    // public $timestamps = false;
    protected $primaryKey = 'element_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'element_id',
        // 'owner_id',
        // 'type',
        'element_name',
        'element_thumbnail',
        'derived_element_type',
        'derived_element_id',
        'created_at',
        'content',
        'hidden',
    ];

    // protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'owner_id' => 'integer',
        'created_at' => 'timestamp',
        'hidden' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function universes(): MorphToMany
    {
        // return $this->morphedByMany(Universe::class, 'elementable', 'elementables',
        // 'element_id', 'elementable_id');
    
        return $this->morphedByMany(Universe::class, 'elementable', 'elementables',
        'element_id', 'elementable_id', 'element_id', 'universe_id');
    }

    public function series(): MorphToMany
    {
        return $this->morphedByMany(Series::class, 'elementable', 'elementables', 'element_id', 'elementable_id');
    }

    public function chapters(): MorphToMany
    {
        return $this->morphedByMany(Chapter::class, 'elementable', 'elementables', 'element_id', 'elementable_id');
    }

    public function pages(): MorphToMany
    {
        return $this->morphedByMany(Page::class, 'elementable', 'elementables', 'element_id', 'elementable_id');
    }

    public function elementType(): MorphTo
    {
        return $this->morphTo('elementType', 'derived_element_type', 'derived_element_id');
    }

    public function elementable(): MorphTo
    {
        return $this->morphTo();
    }

    // relationship - subelements. many to many. Not polymorphic
    // Using the container_elements table, which has parent_element_id and child_element_id columns
    public function childelements(): BelongsToMany
    {
        return $this->belongsToMany(Element::class, 'container_elements', 'parent_element_id', 'child_element_id');
    }

    public function parentelements(): BelongsToMany
    {
        return $this->belongsToMany(Element::class, 'container_elements', 'child_element_id', 'parent_element_id');
    }
    

    public function getRecentsFormattedEntry()
    {
        return [
            'title' => $this->element_name,
            'thumbnail' => $this->element_thumbnail,
            'link' => route('elements.edit', $this->element_id),
        ];
    }

    public static function getThumbnailCollectionName()
    {
        return 'element_thumbnail';
    }

    public function clearThumbnail(){
        $this->element_thumbnail = null;
    }
    public function scopeFilter($query, array $filters)
    {
        // dd($query);
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereRaw("LOWER(element_name) LIKE CONCAT('%', LOWER(?), '%')", [$search])
                ->orWhereRaw("LOWER(content) LIKE CONCAT('%', LOWER(?), '%')", [$search]);
        });
    }

    public function scopeElementType($query, $elementType)
    {
        $query->where('derived_element_type', $elementType);
    }

    // WORK IN PROGRESS
    // WILL RECEIVE LIST OF AUTHORS AS WELL AS WHETHER TO INCLUDE OR EXCLUDE
    public function scopeAuthors($query, $authors)
    {
        $query->whereHas('owner', function ($query) use ($authors) {
            $query->whereIn('username', $authors);
        });
    }

    // WIP
    // MIGHT NEED TO SWAP OUT WITH ELEMENT_ID
    // MIGHT HAVE TO LOOK INTO CONTAINER ELEMENTS TABLE
    public function scopeIncludedElements($query, $includedElements)
    {
        $query->whereHas('elementType', function ($query) use ($includedElements) {
            $query->whereIn('element_name', $includedElements);
        });
    }

    // WORK IN PROGRESS
    // RECEIVES A CONTENT WHETHER IT BE A SERIES, CHAPTER, OR PAGE
    // MUST SOMEHOW EXCLUDE ELEMENTS THAT COME BEFORE THE CONTENT
    // IF IT'S A SERIES, MUST BE THE SAME SERIES IF USING IN CONJUNCTION WITH SCOPEPRESENTTO
    public function scopePresentFrom($query, $presentFrom)
    {
        $query->where('created_at', '>=', $presentFrom);
    }

    // WORK IN PROGRESS
    // RECEIVES A CONTENT WHETHER IT BE A SERIES, CHAPTER, OR PAGE
    // MUST SOMEHOW EXCLUDE ELEMENTS THAT COME AFTER THE CONTENT
    public function scopePresentTo($query, $presentTo)
    {
        $query->where('created_at', '<=', $presentTo);
    }
}
