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
            'universe' => $universe

        ]);
    }

    // Store the new series
    public function store(Request $request)
    {
        // Validate the request
        $formFields = $request->validate([
            'universe_id' => 'required',
            'series_title' => 'required',
            'series_genre' => 'required',
            'series_summary' => 'required',
        ]);

        if ($request->hasFile('series_thumbnail')) {
            $formFields['series_thumbnail'] = $request->file('series_thumbnail')->store('series_thumbnails', 'public');
        } else {
            // Set it to the black image
            $formFields['series_thumbnail'] = 'series_thumbnails/black.png';
        }

        $formFields['rating'] = 0;

        // Create the series
        $series = Series::create($formFields);

        // Redirect to the series page
        return redirect()->route('publish');
    }

    // Show the series
    public function show(Series $series)
    {
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

        // Redirect to the previous page
        return redirect()->back();
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

    public function publish()
    {
        // Get all the universes that the user owns
        $universes = auth()->user()->universes;

        // Return the publish page
        return Inertia::render('Series/Publish', [
            // Eager loading is when you load a model with its relationships
            'universes' => $universes->load('series'),

        ]);
    }

    public function getSeries(Universe $universe)
    {
        // Get all the series in the universe
        $series = $universe->series;

        // Return the series as a json response
        return response()->json($series);
    }

    public function genres()
    {
        return Inertia::render('Series/Genres');
    }

    public function getGenres()
    {
        // dd('hello');
        // Populate array with genres
        $genres = array(
            'ACTION',
            'ADVENTURE',
            'COMEDY',
            'DRAMA',
            'FANTASY',
            'HORROR',
            'MYSTERY',
            'ROMANCE',
            'THRILLER',
        );

        // Return the genres as a json response
        return response()->json($genres);
    }

    public function getGenreSeries(Request $request)
    {
        // Get the genre from the request
        $genre = $request->genre;

        // Get all the series with the genre
        $series = Series::where('series_genre', $genre)->get();

        // Return the series as a json response
        return response()->json($series);
    }

    public function getPopularSeries(Request $request)
    {
        // Get genre from the request
        $genre = $request->genre;

        // dd($genre);

        // Get the top 10 series with the highest rating. If no genre is specified, get the top 10 series with the highest rating
        if ($genre == null) {
            $series = Series::orderBy('rating', 'desc')->take(10)->get();
        } else {
            $series = Series::where('series_genre', $genre)->orderBy('rating', 'desc')->take(10)->get();
        }

        // Attach the universe to each series
        foreach ($series as $seriesSingle) {
            $seriesSingle->universe = $seriesSingle->universe;
        }

        // Return the series as a json response
        return response()->json($series);
    }

    public function popular(){
        return Inertia::render('Series/Popular');
    }

    public function getRecentSeries(){
        // From series table, get the 10 most recent series
        $series = Series::orderBy('created_at', 'desc')->take(10)->get();



        // Return the series as a json response
        return response()->json($series);
    }
}
