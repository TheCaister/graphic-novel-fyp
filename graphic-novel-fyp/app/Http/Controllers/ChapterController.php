<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
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
    public function create()
    {
        //
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
    public function show(Chapter $chapter)
    {
        //
        return Inertia::render('Chapters/Show', [
            'series' => $chapter->series,
            'chapter' => $chapter,
            // 'series' => $chapter->series,
            'pages' => $chapter->pages,
            
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