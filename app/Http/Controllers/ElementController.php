<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Element;
use App\Models\MindmapElement;
use App\Models\Page;
use App\Models\PanelPlannerElement;
use App\Models\Series;
use App\Models\SimpleTextElement;
use App\Models\TemporaryFile;
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

        $element = $this->createElement();

        if(is_null($element)){
            return redirect()->back();
        }

        $tempThumbnail = TemporaryFile::where('folder', $request->upload)->first();

        if ($tempThumbnail) {
            $element->addMedia(storage_path('app/public/uploads/element_thumbnail/tmp/' . $tempThumbnail->folder . '/' . $tempThumbnail->filename))->toMediaCollection('element_thumbnail');

            rmdir(storage_path('app/public/uploads/element_thumbnail/tmp/' . $tempThumbnail->folder));

            $element->element_thumbnail = $element->getFirstMediaUrl('element_thumbnail');
            $element->save();

            $tempThumbnail->delete();
        }
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
        // dd($element);


        return Inertia::render(
            'Elements/EditElementLayout',
            [
                'element' => $element,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Element $element)
    {
        // convert $element->content to object
        $element->content = json_decode($element->content);

        $universe = $element->universes->first();

        if (request()->has('content_type')) {
            return Inertia::render(
                'Elements/EditElementLayout',
                [
                    'element' => $element,
                    'parentContentType' => request()->content_type,
                    'parentContentId' => intval(request()->content_id),
                ]
            );
        }
        else{
            return Inertia::render(
                'Elements/EditElementLayout',
                [
                    'element' => $element,
                    'parentContentType' => 'universes',
                    'parentContentId' => $universe->universe_id,
                ]
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Element $element)
    {

        // dd($request->all());
        $newElement = $request->element;

        // By default, it seems that Laravel converts empty strings or strings with just spaces to null. Here's a recursive function to convert all null text values to empty strings. This is to ensure that the content renders properly. For TipTap, as it doesn't render properly if the text value is null.
        if (!is_null($newElement['content'])) {
            array_walk_recursive($newElement['content'], function (&$value, $key) {
                if ($key === 'text' && is_null($value)) {
                    $value = ' ';
                }
            });
        }


        $tempThumbnail = TemporaryFile::where('folder', $request->upload)->first();


        if ($tempThumbnail) {
            $element->addMedia(storage_path('app/public/uploads/element_thumbnail/tmp/' . $tempThumbnail->folder . '/' . $tempThumbnail->filename))->toMediaCollection('element_thumbnail');

            rmdir(storage_path('app/public/uploads/element_thumbnail/tmp/' . $tempThumbnail->folder));

            $element->element_thumbnail = $element->getFirstMediaUrl('element_thumbnail');

            $tempThumbnail->delete();
        }

        $element->update([
            'element_name' => $newElement['element_name'],

            'content' => $newElement['content'],
            // 'hidden' => $request->hidden,
        ]);

        if ($request->assign) {
            return redirect()->route(
                'elements.assign',
                [
                    'content_type' => $request->content_type,
                    'content_id' => $request->content_id,
                    'preSelectedElements' => $request->preSelectedElements ? $request->preSelectedElements : [],
                ]
            );
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

            // dd($subContentList);

        return Inertia::render('Elements/Assign/AssignElements', [
            'parentContent' => $selectedContent,
            'subContentList' => $subContentList,
            'preSelectedElements' => $request->preSelectedElements ? $request->preSelectedElements : [],
            'elementList' => $this->getUniverse($request->content_type, $request->content_id)->elements->unique(),
        ]);
    }

    // Going up a level in the assigning page
    public function assignGetParent(Request $request)
    {
        $content_type = $request->type;
        $content_id = $request->content_id;
        $parent_content_type = null;

        $parent = null;

        // Switch statement to determine which model to use
        // For example, if we're currently on SeriesView, then the parent model is a Universe
        switch ($content_type) {
            case 'series':
                $model = Series::find($content_id);
                $parent = $model->universe;
                $parent = $this->generateSelectedContent('universes', $parent->universe_id);
                $parent_content_type = 'universes';
                break;
            case 'chapters':
                $model = Chapter::find($content_id);
                $parent = $model->series;

                $parent = $this->generateSelectedContent('series', $parent->series_id);
                $parent_content_type = 'series';
                break;

            case 'pages':
                $model = Page::find($content_id);
                $parent = $model->chapter;

                $parent = $this->generateSelectedContent('chapters', $parent->chapter_id);
                $parent_content_type = 'chapters';
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

    public function getElements()
    {
        // Get the type of content. It could be universes, series, chapters, or pages. With content_id, we can get the content.

        // Set content to nothing
        $content = null;

        $content = $this->getElementable(request()->type, request()->content_id);

        $elements = $content->elements;

        return response()->json($elements);
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
                $selectedContent['content_name'] = 'C' . $content->chapter->chapter_number . 'P' . $content->page_number;
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

    private function createElement()
    {
        $content = $this->getElementable(request()->contentType, request()->contentId);

        $elementType = null;

        $element = Element::create([
            'element_name' => 'New Element',
            'hidden' => false,
        ]);


        switch (request()->elementType) {
            case 'SimpleText':
                $elementType = SimpleTextElement::create();
                break;
            case 'MindMap':
                $elementType = MindmapElement::create();
                break;
            case 'PanelPlanner':
                $elementType = PanelPlannerElement::create();
                break;
            default:
                return null;
        }

        if (is_null($elementType)) {
            return null;
        } else {
            $element->elementType()->associate($elementType);

            $element->save();

            $content->elements()->attach($element->element_id);

            return $element;
        }
    }
}
