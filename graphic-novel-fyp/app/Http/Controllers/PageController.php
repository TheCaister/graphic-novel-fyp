<?php

namespace App\Http\Controllers;

use App\Models\Chapter;

class PageController extends Controller
{
    //
    public function index(Chapter $chapter)
    {
        $pages = $chapter->pages()->paginate(1);
        return response()->json($pages);
    }

    public function getChapterPages(Chapter $chapter)
    {
        $pages = $chapter->pages()->get();

        // Sort pages by page number
        $pages = $pages->sortBy('page_number');

        return response()->json($pages);
    }
}
