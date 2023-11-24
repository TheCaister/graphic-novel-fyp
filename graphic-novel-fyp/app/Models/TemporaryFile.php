<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder',
        'filename',
    ];

    // Method to get the full path of the file
    public function getFullPath(string $media)
    {
        return '/uploads/' . $media . '/tmp/' . $this->folder . '/' . $this->filename;
    }
}
