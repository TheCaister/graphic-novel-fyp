<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends Model implements HasMedia
{ 
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'page_id';

    use InteractsWithMedia;

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

    function delete()
    {
        // $this->elements()->delete();
        parent::delete();
    }

    public static function getThumbnailCollectionName(){
        return 'page_image';
    }

    public function clearThumbnail(){
        // $this->delete();

        $this->page_image = null;
    }
}
