<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    //

    public function search(){
        // dd(request()->all());

        return Inertia::render('Search/SearchLayout', [
            'searchType' => 'elements',
        ]);
    }
}
