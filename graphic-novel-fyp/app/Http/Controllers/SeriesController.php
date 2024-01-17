<?php

namespace App\Http\Controllers;

use App\Models\Series;
use App\Models\TemporaryFile;
use App\Models\Universe;
use Illuminate\Http\Request;
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
            'series_summary' => 'nullable',
        ]);

        if ($request->hasFile('series_thumbnail')) {
            $formFields['series_thumbnail'] = $request->file('series_thumbnail')->store('series_thumbnails', 'public');
        } else {
            // Set it to the black image
            $formFields['series_thumbnail'] = 'series_thumbnails/black.png';
        }

        $tempThumbnail = TemporaryFile::where('folder', $request->upload)->first();

        // Create the series
        $series = Series::create($formFields);

        if ($tempThumbnail) {
            $series->addMedia(storage_path('app/public/uploads/series_thumbnail/tmp/' . $tempThumbnail->folder . '/' . $tempThumbnail->filename))->toMediaCollection('series_thumbnail');

            rmdir(storage_path('app/public/uploads/series_thumbnail/tmp/' . $tempThumbnail->folder));

            // Set series thumbnail to the uploaded thumbnail
            $series->series_thumbnail = $series->getFirstMediaUrl('series_thumbnail');
            $series->save();

            $tempThumbnail->delete();
        }

        return back();
    }

    // Show the series
    public function show(Series $series)
    {
        return Inertia::render(
            'Dashboard/Dashboard',
            [
                'dashboardViewType' => 'ChapterView',
                'parentContentId' => $series->series_id,
            ]
        );
    }

    // Edit the series
    public function edit(Series $series)
    {
        $files = [];

        // If the series has a thumbnail...
        if ($series->series_thumbnail) {
            // Prepare files for filepond. Get the URL of the files
            $filePath = $series->getFirstMediaUrl('series_thumbnail');

            // Get rid of localhost in the URL
            $filePath = str_replace('http://localhost', '', $filePath);

            // In a files array, add the source and the options. Set type to local
            $files = [
                [
                    'source' => $filePath,
                    'options' => [
                        'type' => 'local',
                    ],
                ],
            ];
        }

        return Inertia::render('Series/Edit', [
            'universe' => $series->universe,
            'series' => $series,
            'passedFiles' => $files,
        ]);
    }

    // Update the series
    public function update(Request $request, Series $series)
    {
        // Validate the request
        $formFields = $request->validate([
            // 'universe_id' => 'required',
            'series_title' => 'required',
            'series_genre' => 'required',
            'series_summary' => 'nullable',
        ]);

        // If upload is empty, remove the series_thumbnail from the series media collection
        if ($request->upload == '') {
            $series->clearMediaCollection('series_thumbnail');

            // Set series thumbnail to null
            $series->series_thumbnail = '';
        }

        $tempThumbnail = TemporaryFile::where('folder', $request->upload)->first();

        if ($tempThumbnail) {
            $series->addMedia(storage_path('app/public/uploads/series_thumbnail/tmp/' . $tempThumbnail->folder . '/' . $tempThumbnail->filename))->toMediaCollection('series_thumbnail');

            rmdir(storage_path('app/public/uploads/series_thumbnail/tmp/' . $tempThumbnail->folder));

            // Set series thumbnail to the uploaded thumbnail
            $series->series_thumbnail = $series->getFirstMediaUrl('series_thumbnail');

            $tempThumbnail->delete();
        }


        // Update the series
        $series->update($formFields);

        return redirect()->back();
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

    public function getRecentSeries()
    {
        // From series table, get the 10 most recent series
        $series = Series::orderBy('created_at', 'desc')->take(10)->get();

        // Return the series as a json response
        return response()->json($series);
    }

    public function getSeriesChapters(Series $series)
    {
        // dd($series->chapters);

        // Get all the chapters in the series
        $chapters = $series->chapters;

        // Return the chapters as a json response
        return response()->json($chapters);
    }

    public function manageChapters(Series $series)
    {

        // Return the manage chapter page
        return Inertia::render('Chapters/ManageChapters', [
            'chapters' => $series->chapters,
        ]);
    }

    public function getJsonSeries(Series $series)
    {
        // Return the series as a json response
        return response()->json($series);
    }
}
