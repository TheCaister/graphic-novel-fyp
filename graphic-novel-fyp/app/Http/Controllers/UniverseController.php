<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use App\Models\Universe;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UniverseController extends Controller
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
        return Inertia::render('Universes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $formFields = $request->validate([
            'universe_name' => 'required',
        ]);

        $formFields['owner_id'] = auth()->id();

        // Connecting temporary thumbnail to the universe
        $tempThumbnail = TemporaryFile::where('folder', $request->upload)->first();

        $universe = Universe::create($formFields);

        if ($tempThumbnail) {
            $universe->addMedia(storage_path('app/public/uploads/universe_thumbnail/tmp/' . $request->upload . '/' . $tempThumbnail->filename))
                ->toMediaCollection('universe_thumbnail');

            $universe->save();

            rmdir(storage_path('app/public/uploads/universe_thumbnail/tmp/' . $request->upload));
            $tempThumbnail->delete();
        }

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show(Universe $universe)
    {
        // dd($universe->universe_id);

        return Inertia::render('Dashboard/Dashboard',
        [
            'dashboardViewType' => 'SeriesView',
            'parentContentId' => $universe->universe_id,   
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Universe $universe)
    {
        //
        return Inertia::render('Universes/Edit',
            [
                'universe' => $universe,
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Universe $universe)
    {
        //
        $formFields = $request->validate([
            'universe_name' => 'required',
        ]);

        $universe->update($formFields);
        
        // Redirect back to the dashboard
        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Universe $universe)
    {
        //
        $universe->delete();

        return redirect()->route('home');
    }

    // Return universes in JSON format
    public function getUniverses(Request $request)
    {
        // Get all universe owned by the user in the request
        $universes = Universe::where('owner_id', $request->user_id)->get();

        foreach ($universes as $universe) {
            $universe->thumbnail = $universe->getFirstMediaUrl('universe_thumbnail');
            }

        return response()->json($universes);
    }
}
