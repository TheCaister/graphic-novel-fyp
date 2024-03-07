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
    public function store()
    {

        // dd(request()->all());

        $elementable = request()->contentType;
        $elementable_id = request()->contentId;

        $element = $this->createElement();

        if (is_null($element)) {
            return redirect()->back();
        }

        $tempThumbnail = TemporaryFile::where('folder', request()->upload)->first();

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

            // dd($universe);

            // Attach the element to the universe in case it gets detached from the subcontent
            $universe->elements()->attach($element->element_id);
        }

        // Redirect to the element page
        return redirect()->route('elements.edit', [
            'element' => $element->element_id,
            'contentType' => $elementable,
            'contentId' => $elementable_id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Element $element)
    {

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

        if (request()->has('contentType')) {
            //  dd(intval(request()->content_id));
            return Inertia::render(
                'Elements/EditElementLayout',
                [
                    'element' => $element,
                    'parentContentType' => request()->contentType,
                    'parentContentId' => intval(request()->content_id),
                ]
            );
        } else {
            return Inertia::render(
                'Elements/EditElementLayout',
                [
                    'element' => $element,
                    'parentContentType' => 'Universe',
                    'parentContentId' => $universe->universe_id,
                ]
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Element $element)
    {
        // dd(request()->all());
        $newElement = request()->element;
        $elementContent = request()->element_content;
        $upload = request()->upload;
        $assign = request()->assign;
        $contentType = request()->contentType;
        $contentId = request()->content_id;
        $preSelectedElements = request()->preSelectedElements;
        $hidden = request()->hidden;
        $childrenElements = request()->childrenElements;

        // By default, it seems that Laravel converts empty strings or strings with just spaces to null. Here's a recursive function to convert all null text values to empty strings. This is to ensure that the content renders properly. For TipTap, as it doesn't render properly if the text value is null.
        if (!is_null($elementContent)) {
            array_walk_recursive($elementContent, function (&$value, $key) {
                if ($key === 'text' && is_null($value)) {
                    $value = ' ';
                }
            });
        }

        // if (!is_null($newElement['content'])) {
        //     array_walk_recursive($newElement['content'], function (&$value, $key) {
        //         if ($key === 'text' && is_null($value)) {
        //             $value = ' ';
        //         }
        //     });
        // }

        $tempThumbnail = TemporaryFile::where('folder', $upload)->first();

        if ($tempThumbnail) {
            $element->addMedia(storage_path('app/public/uploads/element_thumbnail/tmp/' . $tempThumbnail->folder . '/' . $tempThumbnail->filename))->toMediaCollection('element_thumbnail');

            rmdir(storage_path('app/public/uploads/element_thumbnail/tmp/' . $tempThumbnail->folder));

            $element->element_thumbnail = $element->getFirstMediaUrl('element_thumbnail');

            $tempThumbnail->delete();
        }

        $element->update([
            'element_name' => $newElement['element_name'],
            'content' => $elementContent,
        ]);

        if ($childrenElements) {
            // Get the IDs of existing child elements
            $existingChildren = Element::whereIn('element_id', $childrenElements)->get()->pluck('element_id');

            // Sync only the existing child elements
            $element->childelements()->sync($existingChildren);
        }

        if ($assign) {
            return redirect()->route(
                'elements.assign',
                [
                    'contentType' => $contentType,
                    'content_id' => $contentId,
                    'preSelectedElements' => $preSelectedElements ? $preSelectedElements : [],
                ]
            );
        } else {
            return;
            // return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Element $element)
    {
        //
        $element->delete();

        // return redirect()->route('dashboard');

        // Redirect to the previous page
        return redirect()->back();
    }

    // public function elementsforge()
    // {

    //     return Inertia::render('Elements/ElementsForge', [
    //         'universes' => auth()->user()->universes,
    //     ]);
    // }

    public function assign()
    {

        // dd(request()->all());

        $selectedContent = null;
        $subContentList = null;

        // Pages are a special case, they don't have subcontent
        if (request()->contentType === 'Page') {
            $page = Page::find(request()->content_id);
            $chapter = $page->chapter;
            $selectedContent = $this->generateSelectedContent('Chapter', $chapter->chapter_id);
        } else {
            $selectedContent = $this->generateSelectedContent(request()->contentType, request()->content_id);
        }

        $subContentList = $this->generateSubcontent(request()->contentType, request()->content_id);


        return Inertia::render('Elements/Assign/AssignElements', [
            'parentContent' => $selectedContent,
            'subContentList' => $subContentList,
            'preSelectedElements' => request()->preSelectedElements ? request()->preSelectedElements : [],
            'elementList' => $this->getUniverse(request()->contentType, request()->content_id)->elements->unique(),
        ]);
    }

    // Going up a level in the assigning page
    public function assignGetParent()
    {
        $contentType = request()->type;
        $content_id = request()->content_id;

        $parentContent = $this->getClassName($contentType)::find($content_id)->getParentContent();

        // redirect to the correct view
        return redirect()->route('elements.assign', [
            'contentType' => $parentContent['content_type'],
            'content_id' => $parentContent['content_id'],
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

        // return $this->assign($request);
        return redirect()->back();
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
        // switch ($type) {
        //     case 'universes':
        //         $content = Universe::find($id);
        //         break;
        //     case 'series':
        //         $content = Series::find($id);
        //         break;
        //     case 'chapters':
        //         $content = Chapter::find($id);
        //         break;
        //     case 'pages':
        //         $content = Page::find($id);
        //         break;
        // }

        $content = $this->getClassName($type)::find($id);

        return $content;
    }

    public function getUniverse($type, $id)
    {




        $universe = null;

        if ($type == 'Universe') {
            $universe = Universe::find($id);
        } else {
            $universe = $this->getClassName($type)::find($id)->universe;
        }

        return $universe;
    }

    public function generateSubcontent($type, $id)
    {
        // Set content to nothing
        $subcontent = [];

        $content = $this->getClassName($type)::find($id);

        // Create a switch statement to determine the type of content
        switch ($type) {
            case 'Universe':
                // Loop through each series, use generateSelectedContent to create a new array with the type and name of the series
                foreach ($content->series as $series) {
                    $subcontent[] = $this->generateSelectedContent('Series', $series->series_id);
                }

                break;
            case 'Series':
                foreach ($content->chapters as $chapter) {
                    $subcontent[] = $this->generateSelectedContent('Chapter', $chapter->chapter_id);
                }
                break;
            case 'Chapter':

                foreach ($content->pages as $page) {
                    $subcontent[] = $this->generateSelectedContent('Page', $page->page_id);
                }
                break;
        }

        // dd($subcontent);

        return $subcontent;
    }

    public function generateSelectedContent($type, $id, $includeDescription = false)
    {

        $selectedContent = $this->getClassName($type)::find($id)->getAssignFormattedEntry($includeDescription);

        $selectedContent['type'] = $type;

        return $selectedContent;
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

    private function getClassName($contentType)
    {
        return 'App\\Models\\' . $contentType;
    }
}
