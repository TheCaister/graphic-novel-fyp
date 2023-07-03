<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Series;
use COM;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChapterController extends Controller
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
    public function create(Series $series)
    {
        // Get the universe of the series
        $universe = $series->universe;
        // Return the create page, passing in the series
        return Inertia::render(
            'Chapters/Create',
            [
                'passedUniverse' => $universe,
                'passedSeries' => $series,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $formFields = $request->validate([
            'series_id' => 'required',
            'chapter_title' => 'required',
            'chapter_notes' => 'required',
            'comments_enabled' => 'required',
        ]);

        if($request->hasFile('chapter_thumbnail')){
            $formFields['chapter_thumbnail'] = $request->file('chapter_thumbnail')->store('chapter_thumbnails', 'public');
        }
        else{
            // Set it to the black image
            $formFields['chapter_thumbnail'] = 'chapter_thumbnails/black.png';
        }

        // From the series, get the highest chapter number. Then, add to it to get the next chapter number
        $nextChapterNumber = Chapter::where('series_id', $formFields['series_id'])->max('chapter_number') + 1;

        $formFields['chapter_number'] = $nextChapterNumber;

        $chapter = Chapter::create($formFields);

        return redirect()->route('series.show', $chapter->series->series_id);


    }

    /**
     * Display the specified resource.
     */
    public function show(Chapter $chapter)
    {
        //
        return Inertia::render('Chapters/Show', [
            'series' => $chapter->series,
            'chapter' => $chapter,
            // 'series' => $chapter->series,
            // 'pages' => $chapter->pages,

            // paginate the pages
            'pages' => $chapter->pages()->paginate(1),

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapter $chapter)
    {
        //
        return Inertia::render('Chapters/Edit', [
            'chapter' => $chapter,
        ]);
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
    public function destroy(Chapter $chapter)
    {
        $series_id = $chapter->series->series_id;
        $chapter->delete();

        return redirect()->route('series.show', $series_id);
    }
}
