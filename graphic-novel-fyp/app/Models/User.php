<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

// use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_admin',
        'username',
        'password',
        'email',
        'is_banned',
        'bio',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_admin' => 'boolean',
        'is_banned' => 'boolean',
        'created_at' => 'timestamp',
    ];

    public function universes(): HasMany
    {
        return $this->hasMany(Universe::class, 'owner_id', 'id');
    }

    // public function elements(): HasMany
    // {
    //     return $this->hasMany(Element::class, 'owner_id', 'id');
    // }

    public function moderatableUniverses(): MorphToMany
    {
        return $this->morphedByMany(Universe::class, 'moderatable', 'approved_moderators', 'moderator_id', 'moderatable_id', 'id', 'universe_id');
    }

    public function moderatableSeries(): MorphToMany
    {
        return $this->morphedByMany(Series::class, 'moderatable', 'approved_moderators', 'moderator_id', 'moderatable_id', 'id', 'series_id');
    }

    public function elements()
    {
        // return $this->hasManyDeep(
        //     Element::class,
        //     [Universe::class, Series::class, Chapter::class, Page::class],
        //     [
        //         'owner_id',
        //         'universe_id',
        //         'series_id',
        //         'chapter_id',
        //     ],
        //     [
        //         'id',
        //         'universe_id',
        //         'series_id',
        //         'chapter_id',
        //     ]
        // );

        return $this->hasManyDeep(
            Element::class,
            [User::class, Universe::class, Series::class, Chapter::class, Page::class, 'elementables'],
            ['id', 'owner_id', 'universe_id', 'series_id', 'chapter_id', 'elementable_id', 'element_id'],
            [null, null, null, null, null, 'page_id', 'element_i']
        );
    }


    public function delete()
    {
        $this->universes()->delete();
        $this->moderatableUniverses()->detach();
        $this->moderatableSeries()->detach();
        parent::delete();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereRaw("LOWER(username) LIKE CONCAT('%', LOWER(?), '%')", [$search]);
        });
    }

    public function scopeHasUniverse($query, $hasUniverse)
    {

        if ($hasUniverse != null) {
            // $query->has('universes');

            if ($hasUniverse == 'true') {
                $query->has('universes');
            } else {
                $query->doesntHave('universes');
            }
        };
    }

    public function scopeAssignedToExistingContent($query, $assignedToExistingContent, $owner)
    {
        // WIP MUST IMPLEMENT ADMIN FUNCTIONALITY FIRST
        if ($assignedToExistingContent != null) {
            $query->whereHas('moderatableUniverses', function ($query) use ($owner) {
                $query->whereIn('id', function ($query) use ($owner) {
                    $query->select('id')
                        ->from('universes')
                        ->where('owner', $owner);
                });
            })->orWhereHas('moderatableSeries', function ($query) use ($owner) {
                $query->whereHas('universe', function ($query) use ($owner) {
                    $query->where('owner', $owner);
                });
            });
        }
    }
}
