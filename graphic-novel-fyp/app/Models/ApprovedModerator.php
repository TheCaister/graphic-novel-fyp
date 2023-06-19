<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovedModerator extends Model
{
    use HasFactory;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'approver_id',
        'approvee_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'approver_id' => 'integer',
        'approvee_id' => 'integer',
    ];

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvee(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
