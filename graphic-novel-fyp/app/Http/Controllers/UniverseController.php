<?php

namespace App\Http\Controllers;

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

        // dd($formFields);

        Universe::create($formFields);

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show(Universe $universe)
    {
        //
        // Attach each series to the universe
        $universe->series = $universe->series;

        return Inertia::render(
            'Universes/Show',
            [
                'universe' => $universe,
            ]
        );
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
    public function update(Request $request, string $id)
    {
        //
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

        // If with_series is true, attach each series to the universe
        if ($request->with_series) {
            foreach ($universes as $universe) {
                $universe->series = $universe->series;
            }
        }

        return response()->json($universes);
    }
}
