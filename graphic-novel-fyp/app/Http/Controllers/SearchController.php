<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $searchTerm = request()->search;
        // dd($searchTerm);
        $resultsList = [];

        // search for elements
        $resultsList = \App\Models\Element::where('element_name', 'LIKE', "%{$searchTerm}%")->get();

        dd($resultsList);

        return $resultsList;
    }
}
