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
            // 'searchType' => request()->searchType,
            'initResultsList' => $resultsList,
            'searchParams' => request()->all(),
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

        // $resultsList = array_slice($resultsList, 0, request()->limit);

        if (is_array($resultsList)) {
            $resultsList = array_slice($resultsList, 0, request()->limit);
        } else {
            // Assuming $resultsList is a collection
            $resultsList = $resultsList->take(request()->limit);
        }

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

        // dd(request()->all());

        $user = auth('sanctum')->user();



        // Get all universes and moderatableUniverses of the user, then get all the elements of those universes
        $elementsWithSearch = function ($query) {
            $query->filter(request(['search']));

            if (request()->advanced) {
                if (array_key_exists('derivedElementTypes', request()->advanced)) {
                    $tempList = [];
                    foreach (request()->advanced['derivedElementTypes'] as $derivedElementType) {
                        $tempList[] = [
                            'element_type' => $this->getElementTypeName($derivedElementType['derivedElementType']),
                            'include' => $derivedElementType['include'],
                        ];
                    }

                    $query->elementType($tempList);
                }
            }
        };

        // Using eager loading
        if ($contentType === null) {
            $resultsList = $user->universes()->with(['elements' => $elementsWithSearch])
                ->get()
                ->concat($user->moderatableUniverses()->with(['elements' => $elementsWithSearch])
                    ->get())
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
        // dd(request()->all());

        // check if request()->advanced exists
        $hasUniverse = null;
        $assignedToExisting = null;

        if (request()->advanced) {

            $hasUniverse = array_key_exists('hasUniverse', request()->advanced) && request()->advanced['hasUniverse'] === 'true';
            $assignedToExisting = array_key_exists('assignedToExisting', request()->advanced) && request()->advanced['assignedToExisting'] === 'true';
        }

        $resultsList = [];

        $user = auth('sanctum')->user();

        $resultsList = User::latest()
            ->filter(request(['search']))
            ->hasUniverse($hasUniverse)
            ->assignedToExistingContent($assignedToExisting, $user)
            ->limit(request()->limit)
            ->get();

        return $resultsList;
    }

    private function searchContent()
    {


        // dd(request()->all());
        $user = auth('sanctum')->user();

        $includedElements = isset(request('advanced')['includedElements'])
            ? array_map('intval', request('advanced')['includedElements'])
            : [];


        $includeTypes = [];
        $excludeTypes = [];

        if (isset(request('advanced')['contentTypes'])) {
            foreach (request('advanced')['contentTypes'] as $type) {
                if ($type['include']) {
                    $includeTypes[] = $type['contentType'];
                } else {
                    $excludeTypes[] = $type['contentType'];
                }
            }
        }

        // dd($includeTypes, $excludeTypes);

        $resultsList = [];


        // From this user, get all universes, series, chapters and pages, put them all through the same filters. Afterwards, place a limit if necessary.

        // We loop through all types of models



        $tempList = collect();

        $types = ['Universes', 'Series', 'Chapters', 'Pages'];



        foreach ($types as $type) {
            if (!empty($includeTypes) && !in_array($type, $includeTypes)) {
                continue;
            }

            if (!empty($excludeTypes) && in_array($type, $excludeTypes)) {
                continue;
            }

            // // concat user moderatableuniverses, moderatableseries, moderatablechapters while also filtering
            $moderatableMethod = 'moderatable' . $type;

            if ($type !== 'Pages') {
                $tempList = $tempList->concat($user->$moderatableMethod()->filter(request(['search']))->limit(request()->limit)->get());
            }

            $method = lcfirst($type);

            $tempList = $tempList->concat($user->$method()->filter(request(['search']))
                // ->includedElements($includedElements)
                ->limit(request()->limit)->get());
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

    private function getElementTypeName($elementType)
    {
        return 'App\\Models\\' . $elementType;
    }
}
