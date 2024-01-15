<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\User;
use Inertia\Inertia;

class SearchController extends Controller
{
    //

    public function search()
    {
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
            'searchType' => request()->searchType,
        ]);
    }

    public function searchJson()
    {

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

        return $resultsList;
    }

    private function searchElements()
    {
        $resultsList = [];

        // search for elements
        if (request()->limit) {
            $resultsList = Element::latest()->filter(request(['search']))->limit(request()->limit)->get();
        } else {
            $resultsList = Element::latest()->filter(request(['search']))->get();
        }

        // dd($resultsList);

        return $resultsList;
    }

    private function searchUsers(){
        $resultsList = [];

        // search for users
        if (request()->limit) {
            $resultsList = User::latest()->filter(request(['search']))->limit(request()->limit)->get();
        } else {
            $resultsList = User::latest()->filter(request(['search']))->get();
        }

        // dd($resultsList);

        return $resultsList;
    }

    private function searchContent(){
        $resultsList = [];

        // search for content
        if (request()->limit) {
            $resultsList = Element::latest()->filter(request(['search']))->limit(request()->limit)->get();
        } else {
            $resultsList = Element::latest()->filter(request(['search']))->get();
        }

        // dd($resultsList);

        return $resultsList;
    }
}
