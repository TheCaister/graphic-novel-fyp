<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'comment_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment_id',
        'commenter_id',
        'replying_to',
        'comment_content',
        'commentable_id',
        'commentable_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'commenter_id' => 'integer',
        'replying_to' => 'integer',
        'comment_content' => 'integer',
        'created_at' => 'timestamp',
    ];

    public function commenter(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function comment(): BelongsTo
    // {
    //     return $this->belongsTo(Comment::class, 'replying_to', 'comment_id');
    // }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'replying_to', 'comment_id');
    }

    // Get the parent commentable model (chapter or series).
    public function commentable() : MorphTo
    {
        return $this->morphTo();
    }
}
