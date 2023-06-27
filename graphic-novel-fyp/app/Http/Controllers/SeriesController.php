<?php

namespace App\Http\Controllers;

use App\Models\Series;
use App\Models\Universe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SeriesController extends Controller
{
    // Create some CRUD functions for the Series model
    // Create a new series
    public function create(Universe $universe)
    {
        return Inertia::render('Series/Create', [
            'universe' => $universe,
        ]);
    }

    // Store the new series
    public function store(Request $request)
    {
        // Validate the request
        $formFields = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'universe_id' => 'required',
        ]);

        // Create the series
        $series = Series::create($formFields);

        // Redirect to the series page
        return redirect()->route('series.show', $series->id);
    }

    // Show the series
    public function show(Series $series){
        // dd($series->chapters);
        
        // Get the chapters for the series as a list
        $chapters = $series->chapters()->get();

        // Query the user_series_rating table to get the average rating for the series and round it to 2 decimal places
        $series->rating = round(DB::table('user_series_rating')->where('series_id', $series->series_id)->avg('rating'), 2);

        // For each chapter, add the amount of likes it has
        foreach ($chapters as $chapter) {
            // Query the user_chapter_likes table for the amount of likes
            $chapter->likes = DB::table('user_chapter_likes')->where('chapter_id', $chapter->chapter_id)->count();
        }

        // dd($chapters);

        return Inertia::render('Series/Show', [
            'series' => $series,
            'universe' => $series->universe(),
            'chapters' => $chapters,
            // the author is the owner of this series' universe
            'author' => $series->universe->owner,
        ]);
    }

    // Edit the series
    public function edit(Series $series)
    {
        return Inertia::render('Series/Edit', [
            'series' => $series,
        ]);
    }

    // Update the series
    public function update(Request $request, Series $series)
    {
        // Validate the request
        $formFields = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'universe_id' => 'required',
        ]);

        // Update the series
        $series->update($formFields);

        // Redirect to the series page
        return redirect()->route('series.show', $series->id);
    }

    // Delete the series
    public function destroy(Series $series)
    {
        // Delete the series
        $series->delete();

        // Redirect to the series page
        return redirect()->route('series.index');
    }

    // Show all series
    public function index()
    {

        // Return the series index page
        return Inertia::render('Series/Index', [
            'series' => Series::paginate(10),
        ]);
    }

    // Show all series in a universe
    public function indexUniverse($universe_id)
    {
        // Get all series in the universe
        $series = Series::where('universe_id', $universe_id)->get();

        // Return the series index page
        return Inertia::render('Series/Index', [
            'series' => $series,
        ]);
    }

    public function publish(){
        // Get all the universes that the user owns
        $universes = auth()->user()->universes;

        // Return the publish page
        return Inertia::render('Series/Publish', [
            // Eager loading is when you load a model with its relationships
            'universes' => $universes->load('series'),
            
        ]);
    }
}
