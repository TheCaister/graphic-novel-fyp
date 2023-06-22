<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeriesController extends Controller
{
    // Create some CRUD functions for the Series model
    // Create a new series
    public function create()
    {
        return Inertia::render('Series/Create', [
            // 'universes' => auth()->user()->universes()->get(),
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
        return Inertia::render('Series/Show', [
            'series' => $series,
            'universe' => $series->universe(),
            'chapters' => $series->chapters()->get(),
            // 'author' => $series->author(),
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


}
