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

        $universe->moderators()->sync($request->moderators);

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

        $universe->moderators()->sync($request->moderators);

        $tempThumbnail = TemporaryFile::where('folder', $request->upload)->first();

        if ($tempThumbnail) {
            $universe->addMedia(storage_path('app/public/uploads/universe_thumbnail/tmp/' . $tempThumbnail->folder . '/' . $tempThumbnail->filename))->toMediaCollection('universe_thumbnail');

            rmdir(storage_path('app/public/uploads/universe_thumbnail/tmp/' . $tempThumbnail->folder));

            $tempThumbnail->delete();
        }


        $universe->update($formFields);
        
        // Redirect back to the dashboard
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Universe $universe)
    {
        
        // Delete all media
        $universe->clearMediaCollection('universe_thumbnail');
        
        
        //
        $universe->delete();



        // return redirect()->route('home');

        return redirect()->back();
    }

    // Return universes in JSON format
    public function getUniverses(Request $request)
    {
        $user = auth('sanctum')->user();

        // Get all universe owned by the user in the request as well as user->moderatableUniverses()
        $universes = $user->universes()->get()->concat($user->moderatableUniverses()->get());
        
        $universes->each(function ($universe) {
            $universe->can_edit = true;
        });

        $moderatableSeries = $user->moderatableSeries()->get();

        foreach ($moderatableSeries as $series) {

            $universe = $series->universe()->first();

            // if user is a moderator of the universe, add the universe to the list
            // with canEdit set to true. Otherwise, add the universe to the list 
            // with canEdit set to false. we can check $universe->moderators() to see if the user is a moderator
            if ($universe->moderators()->where('moderator_id', $user->id)->exists()) {
                $universe->can_edit = true;
            } else {
                $universe->can_edit = false;
              
            }
            $universes->push($universe);
        }
        
        
        // $universes = Universe::where('owner_id', $user->id)->get();

        foreach ($universes as $universe) {
            $universe->thumbnail = $universe->getFirstMediaUrl('universe_thumbnail');

            // Get universe moderators
            // $universe->moderators = $universe->moderators()->get();

            $universe->moderators_avatars = $universe->moderators()->get()->map(function ($moderator) {
                return $moderator->getFirstMediaUrl('profile_picture');
            });
            }

        return response()->json($universes);
    }

    public function getJsonUniverse(Universe $universe)
    {

        $universe->thumbnail = $this->getThumbnail($universe);

        return response()->json($universe);
    }


    // JSON wrapper for getThumbnail    
    public function getThumbnailJson(Universe $universe)
    {

        return response()->json($this->getThumbnail($universe));
    }

    public function getThumbnail(Universe $universe)
    {
        $thumbnail = $universe->getFirstMediaUrl('universe_thumbnail');

        if ($thumbnail) {
            $thumbnail = str_replace('http://localhost', '', $thumbnail);

            $files = [
                [
                    'source' => $thumbnail,
                    'options' => [
                        'type' => 'local',
                    ],
                ],
            ];
        }
        

        return $files;
    }


}
