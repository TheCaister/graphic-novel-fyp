<?php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
use Illuminate\Support\Facades\Log;

class CustomTrimStrings extends Middleware
{
    protected function transform($key, $value)
    {
        // Log::log('info', $key);

        if (strpos($key, 'element.content.content') !== false) {
            return $value;
        }

        return parent::transform($key, $value);
    }
}
?>