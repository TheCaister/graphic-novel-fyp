<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Chapter extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'chapter_id';
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return route('chapters.show', $this->chapter_id);
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
        return $this->hasMany(Page::class, 'chapter_id', 'chapter_id');
    }

 
    public function userLikes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_chapter_likes', 'chapter_id', 'user_id');
    }

    // Get all of the chapter's comments.
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // Delete all comments associated with the chapter.
    function delete()
    {
        // $this->pages()->delete();
        $this->comments()->delete();
        $this->elements()->delete();
        parent::delete();
    }

    public function elements(): MorphToMany{
        return $this->morphToMany(Element::class, 'elementable', 'elementables', 'elementable_id', 'element_id');
    }
}
