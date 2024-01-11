<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Element;
use App\Models\MindmapElement;
use App\Models\Page;
use App\Models\Series;
use App\Models\SimpleTextElement;
use App\Models\Universe;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ElementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $elementable = $request->contentType;
        $elementable_id = $request->contentId;

        $element = null;

        switch ($request->elementType) {
            case 'SimpleText':
                # code...
                $element = $this->createSimpleTextElement($request);
                break;
            case 'MindMap':
                # code...
                $element = $this->createMindMapElement();
                break;
            case 'PanelPlanner':
                # code...
                break;

            default:
                return redirect()->back();
                // break;
        }

        // dd($element->universes->first());

        // Check if the element is already attached to a universe
        if (is_null($element->universes->first())) {
            $universe = $this->getUniverse($elementable, $elementable_id);
            // Attach the element to the universe in case it gets detached from the subcontent
            $universe->elements()->attach($element->element_id);
        }

        // Redirect to the element page
        return redirect()->route('elements.edit', $element->element_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Element $element)
    {
        // return Inertia::render('Dashboard/Dashboard',
        // [
        //     'dashboardViewType' => 'SeriesView',
        //     'parentContentId' => $universe->universe_id,   
        // ]);


        return Inertia::render(
            'Elements/EditElementLayout',
            [
                'element' => $element,

            ]
        );

        // return Inertia::render('Elements/Show', [
        //     'element' => $element,
        //     'universes' => $element->universes,
        //     'series' => $element->series,
        //     'chapters' => $element->chapters,
        //     'pages' => $element->pages,
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Element $element, Request $request)
    {
        // convert $element->content to object
        $element->content = json_decode($element->content);




        // $content = null;

        // if($element->universes->count() > 0){
        //     $content = $element->universes->first();
        // }
        // else if($element->series->count() > 0){
        //     $content = $element->series->first();

        // }
        // else if($element->chapters->count() > 0){
        //     $content = $element->chapters->first();

        // }
        // else if($element->pages->count() > 0){
        //     $content = $element->pages->first();
        // }



        // dd($content);
        // dd($request->all());


        return Inertia::render(
            'Elements/EditElementLayout',
            [
                'element' => $element,
                'parentContentType' => $request->content_type,
                'parentContentId' => intval($request->content_id),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Element $element)
    {

        // dd($request->all());
        $newElement = $request->element;

        // $elementContent= $newElement['content'];

        // in elementContent, I want to convert all values of null to empty strings
        // $elementContent = array_map(function($value){
        //     if(is_null($value)){
        //         return '';
        //     }
        //     else{
        //         return $value;
        //     }
        // }, $elementContent);

        // By default, it seems that Laravel converts empty strings or strings with just spaces to null. Here's a recursive function to convert all null text values to empty strings. This is to ensure that the content renders properly. For TipTap, as it doesn't render properly if the text value is null.
        array_walk_recursive($newElement['content'], function(&$value, $key){
            if ($key === 'text' && is_null($value)) {
                $value = ' ';
            }
        });
        
        // dd($newElement['content']);




        // dd(json_encode($newElement['content']));

        $element->update([
            'element_name' => $newElement['element_name'],
            'content' => json_encode($newElement['content']),
            // 'content' => $newElement['content'],
            // 'hidden' => $request->hidden,
        ]);

        if ($request->assign) {
            return redirect()->route(
                'elements.assign',
                [
                    'content_type' => $request->content_type,
                    'content_id' => $request->content_id,
                    'preSelectedElements' => $request->preSelectedElements,
                ]
            );
            // return $this->assign($request);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Element $element)
    {
        //
        $element->delete();

        return redirect()->route('dashboard');
    }

    public function elementsforge()
    {

        return Inertia::render('Elements/ElementsForge', [
            'universes' => auth()->user()->universes,
        ]);
    }

    public function assign(Request $request)
    {

        // // Get the type of content. It could be universes, series, chapters, or pages. With content_id, we can get the content.

        // // Set content to nothing
        // $content = null;

        // $subcontent = null;

        // // Create a new selectedContent vartiable to pass to the view. It will contain the type of content, along with the name of the content.
        // $selectedContent = [
        //     'type' => $request->type,
        // ];

        // dd($request->all());

        $selectedContent = null;
        $subContentList = null;

        $selectedContent = $this->generateSelectedContent($request->content_type, $request->content_id);

        // $content = $this->getElementable($request->type, $request->content_id);
        $subContentList = $this->generateSubcontent($request->content_type, $request->content_id);




        // // Set $elements to the elements of the content, filter duplicates by using unique()
        // $elements = $content->elements->unique();

        return Inertia::render('Elements/Assign/AssignElements', [
            'parentContent' => $selectedContent,
            'subContentList' => $subContentList,
            'preSelectedElements' => $request->preSelectedElements,
            'elementList' => $this->getUniverse($request->content_type, $request->content_id)->elements->unique(),
        ]);
    }

    public function assignGetParent(Request $request)
    {
        // { type: props.parentContent.type, id: props.parentContent.content_id })
        $content_type = $request->type;
        $content_id = $request->content_id;
        $parent_content_type = null;

        $parent = null;

        // Switch statement to determine which model to use
        // For example, if we're currently on SeriesView, then the parent model is a Universe
        switch ($content_type) {
            case 'series':
                // return redirect()->route('home');
                $model = Series::find($content_id);
                $parent = $model->universe;
                $parent = $this->generateSelectedContent('universes', $parent->universe_id);
                $parent_content_type = 'universes';
                break;
            case 'chapters':
                $model = Chapter::find($content_id);
                $parent = $model->series;
                $parent = $this->generateSubcontent('series', $parent->series_id);
                $parent_content_type = 'series';

                // $result['show'] = 'universes';
                // $result['parentid'] = $parent->universe_id;
                break;
            default:
                return redirect()->route('home');
        }
        // redirect to the correct view
        return redirect()->route('elements.assign', [
            'content_type' => $parent_content_type,
            'content_id' => $parent['content_id'],
            'preSelectedElements' => []
        ]);
    }

    public function assignStore(Request $request)
    {

        // $request->preSelectedElements = $request->selectedElementList;

        $request->merge([
            'preSelectedElements' => $request->selectedElementList,
        ]);

        // dd($request->all());
        // I can either split the selected content into a separate array for each type of content, or I can iterate through the array and check the type of content for each element.

        foreach ($request->selectedContentList as $content) {
            $curContent =  $this->getElementable($content['type'], $content['content_id']);

            foreach ($request->selectedElementList as $element) {
                if ($element['checked']) {
                    // dd('attaching...');
                    $curContent->elements()->attach($element['element_id']);
                } else {
                    $curContent->elements()->detach($element['element_id']);
                }
            }
        }

        return $this->assign($request);
    }

    public function getElements(Request $request)
    {
        // Get the type of content. It could be universes, series, chapters, or pages. With content_id, we can get the content.

        // Set content to nothing
        $content = null;

        $content = $this->getElementable($request->type, $request->content_id);

        $elements = $content->elements;

        return response()->json([
            'elements' => $elements,
        ]);
    }

    public function getElementable($type, $id)
    {

        // Set content to nothing
        $content = null;

        // Create a switch statement to determine the type of content
        switch ($type) {
            case 'universes':
                $content = Universe::find($id);
                break;
            case 'series':
                $content = Series::find($id);
                break;
            case 'chapters':
                $content = Chapter::find($id);
                break;
            case 'pages':
                $content = Page::find($id);
                break;
        }

        return $content;
    }

    public function getUniverse($type, $id)
    {

        $universe = null;

        if ($type == 'universes') {
            $universe = Universe::find($id);
        } else if ($type == 'series') {
            $universe = Series::find($id)->universe;
        } else if ($type == 'chapters') {
            $universe = Chapter::find($id)->series->universe;
        } else if ($type == 'pages') {
            $universe = Page::find($id)->chapter->series->universe;
        }

        return $universe;
    }

    public function generateSubcontent($type, $id)
    {
        // Set content to nothing
        $subcontent = [];

        // dd($type, $id);

        // Create a switch statement to determine the type of content
        switch ($type) {
            case 'universes':
                $content = Universe::find($id);

                // dd($content->series);
                // $subcontent = $content->series;

                // Loop through each series, use generateSelectedContent to create a new array with the type and name of the series
                foreach ($content->series as $series) {
                    $subcontent[] = $this->generateSelectedContent('series', $series->series_id);
                }

                break;
            case 'series':
                $content = Series::find($id);
                // $subcontent = $content->chapters;

                foreach ($content->chapters as $chapter) {
                    $subcontent[] = $this->generateSelectedContent('chapters', $chapter->chapter_id);
                }
                break;
            case 'chapters':
                $content = Chapter::find($id);
                // $subcontent = $content->pages;

                foreach ($content->pages as $page) {
                    $subcontent[] = $this->generateSelectedContent('pages', $page->page_id);
                }
                break;
        }



        return $subcontent;
    }

    public function generateSelectedContent($type, $id, $includeDescription = false)
    {

        $selectedContent = [
            'type' => $type,
        ];

        // Create a switch statement to determine the type of content
        switch ($type) {
            case 'universes':
                $content = Universe::find($id);
                $selectedContent['content_id'] = $content->universe_id;
                $selectedContent['content_name'] = $content->universe_name;
                // $selectedContent['thumbnail'] = $content->universe_thumbnail;
                break;
            case 'series':
                $content = Series::find($id);
                $selectedContent['content_id'] = $content->series_id;
                $selectedContent['content_name'] = $content->series_title;

                if ($includeDescription) {
                    $selectedContent['description'] = $content->series_summary;
                }
                break;
            case 'chapters':
                $content = Chapter::find($id);
                $selectedContent['content_id'] = $content->chapter_id;
                $selectedContent['content_name'] = $content->chapter_title;

                if ($includeDescription) {
                    $selectedContent['description'] = $content->chapter_notes;
                }
                break;
            case 'pages':
                $content = Page::find($id);
                $selectedContent['content_id'] = $content->page_id;
                // $selectedContent['name'] = $content->page_title;

                // Name format will be something like C3P4. This means we need to first get the chapter number, then the page number.
                $selectedContent['name'] = 'C' . $content->chapter->chapter_number . 'P' . $content->page_number;
                break;
        }

        return $selectedContent;
    }

    public function forgeShow(Request $request)
    {

        // dd($request->all());

        // return Inertia::render('Elements/Forge/Show', [
        //     'selectedContent' => $this->generateSelectedContent($request->contentType, $request->contentId),
        //     'content' => $this->getElementable($request->contentType, $request->contentId),
        //     'subContentList' => $this->getSubcontent($request->contentType, $request->contentId),
        // ]);
    }

    private function createSimpleTextElement(Request $request)
    {
        $content = $this->getElementable($request->contentType, $request->contentId);

        $simple_text = SimpleTextElement::create();

        // We get the content, and then we create a new element and attach it to the content.
        $element = Element::create([
            'element_name' => 'New Element',
            'hidden' => false,
        ]);

        $element->elementType()->associate($simple_text);

        $element->save();

        $content->elements()->attach($element->element_id);

        return $element;
    }

    private function createMindMapElement(){
        $content = $this->getElementable(request()->contentType, request()->contentId);

        $mindmap = MindmapElement::create();

        // We get the content, and then we create a new element and attach it to the content.

        $element = Element::create([
            'element_name' => 'New Element',
            'hidden' => false,
        ]);

        $element->elementType()->associate($mindmap);

        $element->save();

        $content->elements()->attach($element->element_id);

        return $element;
    }
}
