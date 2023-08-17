<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Element;
use App\Models\Page;
use App\Models\Series;
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
        //

        return Inertia::render('Elements/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Element $element)
    {
        //
        return Inertia::render('Elements/Show', [
            'element' => $element,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function elementsforge()
    {

        return Inertia::render('Profile/Main', [
            'user' => auth()->user(),
            'passed_tab' => 'ElementsForge',
        ]);
    }

    public function assign(Request $request)
    {

        // Get the type of content. It could be universes, series, chapters, or pages. With content_id, we can get the content.

        // Set content to nothing
        $content = null;
        $subcontent = null;

        // Create a new selectedContent vartiable to pass to the view. It will contain the type of content, along with the name of the content.
        $selectedContent = [
            'type' => $request->type,
            // 'name' => $content->name,
        ];

        // Create a switch statement to determine the type of content
        switch ($request->type) {
            case 'universes':
                $content = Universe::find($request->content_id);
                // Set name in selectedContent to the name of the universe
                $selectedContent['name'] = $content->universe_name;
                $subcontent = $content->series;
                break;
            case 'series':
                $content = Series::find($request->content_id);
                $selectedContent['name'] = $content->series_title;
                $subcontent = $content->chapters;
                break;
            case 'chapters':
                $content = Chapter::find($request->content_id);
                $selectedContent['name'] = $content->chapter_title;

                $subcontent = $content->pages;
                break;
            case 'pages':
                $content = Page::find($request->content_id);
                break;
        }

        // dd($type);

        return Inertia::render('Elements/AssignElements', [
            'selectedContent' => $selectedContent,
            'content' => $content,
            'elements' => $content->elements,
            // 'subContentList' => $subcontent,
        ]);
    }

    public function getElements(Request $request)
    {
        // Get the type of content. It could be universes, series, chapters, or pages. With content_id, we can get the content.

        // Set content to nothing
        $content = null;

        // Create a switch statement to determine the type of content
        switch ($request->type) {
            case 'universes':
                $content = Universe::find($request->content_id);
                break;
            case 'series':
                $content = Series::find($request->content_id);
                break;
            case 'chapters':
                $content = Chapter::find($request->content_id);
                break;
            case 'pages':
                $content = Page::find($request->content_id);
                break;
        }

        $elements = $content->elements;

        return response()->json([
            'elements' => $elements,
        ]);
    }
}
