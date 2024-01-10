<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Element extends Model
{
    use HasFactory;
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

    public function elementType(): MorphTo
    {
        return $this->morphTo('elementType', 'derived_element_type', 'derived_element_id');
    }

    public function scopeFilter($query, array $filters)
    {
        // dd($query);
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereRaw("LOWER(element_name) LIKE CONCAT('%', LOWER(?), '%')", [$search])
                ->orWhereRaw("LOWER(content) LIKE CONCAT('%', LOWER(?), '%')", [$search]);
        });
    }
}
