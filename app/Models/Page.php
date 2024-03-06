<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Page extends Model implements HasMedia
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'page_id';

    use InteractsWithMedia;
    use HasRelationships;


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('page_image')->singleFile();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id',
        'chapter_id',
        'page_number',
        'page_image',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'chapter_id' => 'integer',
        'page_number' => 'integer',
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'chapter_id', 'chapter_id');
    }

    public function elements(): MorphToMany
    {
        return $this->morphToMany(Element::class, 'elementable', 'elementables', 'elementable_id', 'element_id');
    }

    public function owner()
    {
        return $this->hasOneDeep(User::class, [Chapter::class, Series::class, Universe::class], ['chapter_id', 'series_id', 'universe_id', 'owner_id', 'id']);
    }

    public function universe(){
        return $this->hasOneDeep(Universe::class, [Chapter::class, Series::class], ['chapter_id', 'series_id', 'universe_id']);
    }

    function delete()
    {
        // $this->elements()->delete();
        parent::delete();
    }

    public static function getThumbnailCollectionName()
    {
        return 'page_image';
    }

    public function clearThumbnail()
    {
        // $this->delete();

        $this->page_image = null;
    }

    public function getSearchFormattedEntry()
    {
        return [
            'title' => 'Page ' . $this->page_number,
            'type' => 'page',
            'thumbnail' => $this->page_image,
            'link' => route('chapters.show', $this->chapter->chapter_id),
        ];
    }

    public function getAssignFormattedEntry($includeDescription = false){
        return [
            'content_id' => $this->page_id,
            'content_name' => 'C' . $this->chapter->chapter_number . 'P' . $this->page_number,
            'content_thumbnail' => $this->page_image,
            'description' => 'Page.',
        ];
    }

    public function getParentContent(){
        return [
            'content_type' => 'Chapter',
            'content_id' => $this->chapter()->first()->chapter_id
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        // dd($query);
        // $query->when($filters['search'] ?? null, function ($query, $search) {
        //     $query->whereRaw("LOWER(universe_name) LIKE CONCAT('%', LOWER(?), '%')", [$search]);
        // });
    }
}
