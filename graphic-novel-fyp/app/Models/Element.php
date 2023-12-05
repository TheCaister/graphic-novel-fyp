<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Element extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'element_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'element_id',
        'owner_id',
        'type',
        'created_at',
        'content',
        'hidden',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'owner_id' => 'integer',
        'created_at' => 'timestamp',
        'hidden' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function universes(): MorphToMany
    {
        return $this->morphedByMany(Universe::class, 'elementable', 'elementables', 'element_id', 'elementable_id');
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
}
