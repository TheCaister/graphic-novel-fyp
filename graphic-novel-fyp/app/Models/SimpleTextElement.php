<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SimpleTextElement extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'simple_text_element_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'simple_text_element_id',
    ];

    public function element(): MorphOne
    {
        return $this->morphOne(Element::class, 'elementType', 'derived_element_type', 'derived_element_id');
    }
}
