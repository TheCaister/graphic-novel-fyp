<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //
    public function index(Chapter $chapter)
    {
        $pages = $chapter->pages()->paginate(1);
        return response()->json($pages);
    }
}
