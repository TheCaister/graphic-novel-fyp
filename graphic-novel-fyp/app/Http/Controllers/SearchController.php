<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\User;
use App\Models\Universe;
use App\Models\Series;
use App\Models\Chapter;
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

        // dd($resultsList);

        return Inertia::render('Search/SearchLayout', [
            'searchType' => request()->searchType,
            'initResultsList' => $resultsList,
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

    private function searchUsers()
    {
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

    private function searchContent()
    {
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

    public function getRecent()
    {
        $resultsList = [];

        // Aggregate universes, series, chapters and elements. Order by updated_at and limit to the request limit
        $tempList = Universe::latest()
            ->limit(request()->limit)->get()
            ->concat(Series::latest()->limit(request()->limit)->get())
            ->concat(Chapter::latest()->limit(request()->limit)->get())
            ->concat(Element::latest()->limit(request()->limit)->get())
            ->sortByDesc('updated_at')
            ->take(request()->limit);

        // dd($tempList);

        // loop through the results and convert them to a formatted entry
        foreach ($tempList as $result) {
            $resultsList[] = $result->getRecentsFormattedEntry();
        }

        // dd($resultsList);

        return $resultsList;
    }
}
