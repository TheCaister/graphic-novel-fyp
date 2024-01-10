<?php

namespace App\Http\Controllers;

use App\Models\Element;
use Inertia\Inertia;

class SearchController extends Controller
{
    //

    public function search(){
        // dd(request()->all());

        $resultsList = [];

        // do a switch on the search type
        switch (request()->searchType) {
            case 'elements':
                $resultsList = $this->searchElements();
                break;
            case 'users':
                $resultsList = $this->searchUsers();
                break;
            case 'content':
                $resultsList = $this->searchContent();
                break;
            default:
                $resultsList = [];
                break;
        }

        return Inertia::render('Search/SearchLayout', [
            'searchType' => 'elements',
        ]);
    }

    private function searchElements(){
        $resultsList = [];

        // search for elements
        $resultsList = Element::latest()->filter(request(['search']))->get();

        // dd($resultsList);

        return $resultsList;
    }
}
