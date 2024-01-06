<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Element;
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
                break;
            case 'PanelPlanner':
                # code...
                break;

            default:
                return redirect()->back();
                // break;
        }

        $universe = $this->getUniverse($elementable, $elementable_id);

        // Attach the element to the universe in case it gets detached from the subcontent
        $universe->elements()->attach($element->element_id);

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
    public function edit(Element $element)
    {
        //
        // return Inertia::render('Elements/Edit', [
        //     'element' => $element,
        // ]);




        // convert $element->content to object
        $element->content = json_decode($element->content);
        // dd($element);

        // $element->content = json_encode([
        //     'title' => 'Random Test Object',
        //     'description' => 'This is a random test object.',
        //     'data' => [
        //         'key1' => 'value1',
        //         'key2' => 'value2',
        //         'key3' => 'value3',
        //     ],
        // ]);

        return Inertia::render(
            'Elements/EditElementLayout',
            [
                'element' => $element,
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

        $element->update([
            'content' => json_encode($newElement['content']),
            // 'hidden' => $request->hidden,
            // 'type' => $request->type,
        ]);

        if ($request->assign) {
            return redirect()->route('elements.assign');
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

    public function assign()
    {

        // // Get the type of content. It could be universes, series, chapters, or pages. With content_id, we can get the content.

        // // Set content to nothing
        // $content = null;

        // $subcontent = null;

        // // Create a new selectedContent vartiable to pass to the view. It will contain the type of content, along with the name of the content.
        // // $selectedContent = [
        // //     'type' => $request->type,
        // // ];

        // $selectedContent = $this->generateSelectedContent($request->type, $request->content_id);

        // $content = $this->getElementable($request->type, $request->content_id);
        // $subcontent = $this->getSubcontent($request->type, $request->content_id);

        // // Set $elements to the elements of the content, filter duplicates by using unique()
        // $elements = $content->elements->unique();

        // return Inertia::render('Elements/AssignElements', [
        //     'selectedContent' => $selectedContent,
        //     'content' => $content,
        //     'elements' => $elements,
        //     'subContentList' => $subcontent,
        // ]);

        return Inertia::render('Elements/AssignElements', [
        ]);
    }

    public function assignStore(Request $request)
    {
        // dd($request->all());

        // I can either split the selected content into a separate array for each type of content, or I can iterate through the array and check the type of content for each element.

        // $contentList = [];

        foreach ($request->selectedContent as $content) {
            // $contentList[] = $this->getElementable($content['optionType'], $content['optionId']);

            $curContent =  $this->getElementable($content['optionType'], $content['optionId']);

            foreach ($request->selectedElements as $element) {
                if ($element['assign']) {
                    $curContent->elements()->attach($element['optionId']);
                } else {
                    $curContent->elements()->detach($element['optionId']);
                }
            }
        }

        return redirect()->route('user.main.elementsforge');

        // return redirect()->back();
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

    public function getSubcontent($type, $id)
    {
        // Set content to nothing
        $subcontent = null;

        // Create a switch statement to determine the type of content
        switch ($type) {
            case 'universes':
                $content = Universe::find($id);
                $subcontent = $content->series;
                break;
            case 'series':
                $content = Series::find($id);
                $subcontent = $content->chapters;
                break;
            case 'chapters':
                $content = Chapter::find($id);
                $subcontent = $content->pages;
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
                $selectedContent['name'] = $content->universe_name;
                break;
            case 'series':
                $content = Series::find($id);
                $selectedContent['name'] = $content->series_title;

                if ($includeDescription) {
                    $selectedContent['description'] = $content->series_summary;
                }
                break;
            case 'chapters':
                $content = Chapter::find($id);
                $selectedContent['name'] = $content->chapter_title;

                if ($includeDescription) {
                    $selectedContent['description'] = $content->chapter_notes;
                }
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
}
