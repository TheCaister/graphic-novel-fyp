<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\User;
use App\Models\Universe;
use App\Models\Series;
use App\Models\Chapter;
use App\Models\Page;
use Inertia\Inertia;

class SearchController extends Controller
{
    //



    public function search()
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

        $resultsList = array_slice($resultsList, 0, request()->limit);
        return response()->json($resultsList);
    }

    // This will get content type and id
    public function getSiblingContent()
    {

        $content = null;
        $id = request()->id;

        $resultsList = [];

        switch (request()->type) {
            case 'universe':
                // $content = Universe::find($id);
                $user = auth('sanctum')->user();
                $resultsList = $user->universes()->select('universe_name as name', 'universe_id as id')->get();
                // dd($resultsList);
                break;
            case 'series':
                $content = Series::find($id);

                // After getting the series, get the universe, then get all
                // the series of that universe
                $universe = $content->universe;

                $resultsList = $universe->series()->select('series_title as name', 'series_id as id')->get();
                break;
            case 'chapter':
                $content = Chapter::find($id);
                $series = $content->series;

                $resultsList = $series->chapters()->select('chapter_title as name', 'chapter_id as id')->get();

                break;
        }

        // dd($resultsList);

        return $resultsList;
    }

    public function searchMention()
    {
        $resultsList = [];

        // do a switch on the search type
        switch (request()->searchType) {
            case 'elements':
                $resultsList = $this->searchElements(request()->contentType, request()->contentId);

                // id = element_id, name = element_name
                foreach ($resultsList as $result) {
                    $result->id = $result->element_id;
                    $result->name = $result->element_name;
                }
                break;
            case 'users':
                $resultsList = $this->searchUsers();

                foreach ($resultsList as $result) {
                    $result->name = $result->username;
                }

                break;
            case 'content':
                $resultsList = $this->searchContent();
                break;
            default:
                $resultsList = [];
                break;
        }

        return response()->json($resultsList);
    }

    private function searchElements($contentType = null, $contentId = null)
    {
        $resultsList = [];

        $user = auth('sanctum')->user();


        // Get all universes and moderatableUniverses of the user, then get all the elements of those universes
        $elementsWithSearch = function ($query) {
            $query->filter(request(['search']));
        };

        // Using eager loading
        if ($contentType === null) {
            $resultsList = $user->universes()->with(['elements' => $elementsWithSearch])
                ->get()
                ->concat($user->moderatableUniverses()->with(['elements' => $elementsWithSearch])->get())
                ->pluck('elements')
                ->flatten();
        } else {
            $content = $this->getClassName($contentType)::find($contentId);
            $resultsList = $content->elements()->filter(request(['search']))->get();
        }


        // Depending on whether to include parent and child elements, concat the results with the parent and child elements
        // The most straightforward approach would be to get a list of all the elements, create another list of parent elements and child elements, then concat the results
        // When including query in parent, for example, if your search is 'Johnny', you'll get a Johnny element, and perhaps a MindMap element that contains Johnny
        // On the other hand, if you search for 'Relationships', you'll get a Relationships element, and all the elements that are contained within it

        return $resultsList;
    }

    private function searchUsers()
    {
        // dd(request()->advanced['hasUniverse']);

        // check if request()->advanced exists
        $hasUniverse = false;

        if (request()->advanced) {

            $hasUniverse = request()->advanced['hasUniverse'] === 'true';
        }

        $resultsList = [];

        $user = auth('sanctum')->user();

        $resultsList = User::latest()->filter(request(['search']))->hasUniverse($hasUniverse)->limit(request()->limit)->get();

        // dd($resultsList);
        
        // ->limit(request()->limit)->get();

        return $resultsList;
    }

    private function searchContent()
    {

        $user = auth('sanctum')->user();

        $resultsList = [];


        // From this user, get all universes, series, chapters and pages, put them all through the same filters. Afterwards, place a limit if necessary.

        // We loop through all types of models



        $tempList = collect();

        $types = ['Universes', 'Series', 'Chapters', 'Pages'];

           // concat user moderatableuniverses, moderatableseries, moderatablechapters while also filtering
           $tempList = $tempList->concat($user->moderatableUniverses()->filter(request(['search']))->limit(request()->limit)->get());

           $tempList = $tempList->concat($user->moderatableSeries()->filter(request(['search']))->limit(request()->limit)->get());
   
           $tempList = $tempList->concat($user->moderatableChapters()->filter(request(['search']))->limit(request()->limit)->get());

        foreach ($types as $type) {
            // if (request('include' . $type) != false) {
                if (true) {
                $method = lcfirst($type);

                $tempList = $tempList->concat($user->$method()->filter(request(['search']))->limit(request()->limit)->get());
            }
        }

     

        $tempList = $tempList->sortByDesc('updated_at')
            ->take(request()->limit);

        // loop through the results and convert them to a formatted entry
        foreach ($tempList as $result) {
            $resultsList[] = $result->getSearchFormattedEntry();
        }
        // dd($resultsList);
        // We do limiting here

        return $resultsList;
    }

    public function getAssignedElements()
    {
        $resultsList = [];

        // I have request()->type and request()->contentIdList
        // I will get a list of content based on the type and id

        $contentList = $this->getClassName(request()->type)::find(request()->contentIdList);

        // From contentlist, append to the resultsList the elements and keep it unique
        $resultsList = $contentList->map(function ($content) {
            return $content->elements;
        })->unique('element_id')->values()->toArray();

        return $resultsList;
    }

    public function getRecent()
    {
        $resultsList = [];

        // Aggregate universes, series, chapters and elements. Order by updated_at and limit to the request limit
        // Eventualy, I want to use $contentTypeList here as well. Also, it only gets the latest, not the ones owned by the user.
        $tempList = Universe::latest()
            ->limit(request()->limit)->get()
            ->concat(Series::latest()->limit(request()->limit)->get())
            ->concat(Chapter::latest()->limit(request()->limit)->get())
            ->concat(Element::latest()->limit(request()->limit)->get())
            ->sortByDesc('updated_at')
            ->take(request()->limit);


        // loop through the results and convert them to a formatted entry
        foreach ($tempList as $result) {
            $resultsList[] = $result->getRecentsFormattedEntry();
        }

        return $resultsList;
    }

    private function getClassName($contentType)
    {
        return 'App\\Models\\' . $contentType;
    }
}
