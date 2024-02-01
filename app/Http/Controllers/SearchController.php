<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\User;
use App\Models\Universe;
use App\Models\Series;
use App\Models\Chapter;
use Illuminate\Support\Facades\Auth;
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

        return response()->json($resultsList);
    }

    // This will get content type and id
    public function getSiblingContent(){
        // dd('hey baby');

        $content = null;
        $id = request()->id;

        // dd($id);
        $resultsList = [];

        switch(request()->type){
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

        return $resultsList;
    }

    public function searchMention(){
        $resultsList = [];

        // do a switch on the search type
        switch (request()->searchType) {
            case 'elements':
                $resultsList = $this->searchElements();

                // id = element_id, name = element_name
                foreach($resultsList as $result){
                    $result->id = $result->element_id;
                    $result->name = $result->element_name;
                }
                break;
            case 'users':
                $resultsList = $this->searchUsers();

                foreach($resultsList as $result){
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

    private function searchElements()
    {
        $resultsList = [];

        $user = auth('sanctum')->user();


        $resultsList = $user->elementsThroughUniverse()->filter(request(['search']))->limit(request()->limit)->get();


        // Depending on whether to include parent and child elements, concat the results with the parent and child elements
        // The most straightforward approach would be to get a list of all the elements, create another list of parent elements and child elements, then concat the results
        // When including query in parent, for example, if your search is 'Johnny', you'll get a Johnny element, and perhaps a MindMap element that contains Johnny
        // On the other hand, if you search for 'Relationships', you'll get a Relationships element, and all the elements that are contained within it

        return $resultsList;
        // return 1;
    }

    private function searchUsers()
    {
        $resultsList = [];

        $user = auth('sanctum')->user();

        $resultsList = User::latest()->filter(request(['search']))->hasUniverse(request()->hasUniverse)->limit(request()->limit)->get();

        return $resultsList;
    }

    private function searchContent()
    {

        $user = User::find(request()->userId);

        $resultsList = [];
        $resultsList = Element::latest()->filter(request(['search']))->limit(request()->limit)->get();

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
