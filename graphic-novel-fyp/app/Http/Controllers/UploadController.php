<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    //
    public function store(Request $request)
    {

        // Check if $request->media is set. If not, return an empty string
        $media = $request->media ?? '';

        if ($media == '') {
            return '';
        }

        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = $file->getClientOriginalName();
            $folder = uniqid() . '-' . now()->timestamp;

            $file->storeAs('/uploads/' . $media . '/tmp/' . $folder, $filename, 'public');

            // Store the folder and filename in the database

            TemporaryFile::create([
                'folder' => $folder,
                'filename' => $filename,
            ]);

            return $folder;
        }
        // For multiple files
        else if ($request->hasFile('uploads')) {
            $files = $request->file('uploads');
            $folder = uniqid() . '-' . now()->timestamp;

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $file->storeAs('/uploads/' . $media . '/tmp/' . $folder, $filename, 'public');

                TemporaryFile::create([
                    'folder' => $folder,
                    'filename' => $filename,
                ]);
            }
            return $folder;
        }

        return 'nope';
    }

    public function deleteSeriesThumbnail(string $serverId)
    {
        // Get the temporary file
        $temporaryFile = TemporaryFile::where('folder', $serverId)->first();

        if ($temporaryFile) {
            // Get full path of the file
            $fullPath = $temporaryFile->getFullPath('series_thumbnail');


            if (Storage::disk('public')->exists($fullPath)) {
                Storage::disk('public')->delete($fullPath);
            }
        }

        // Delete the temporary file from the database
        $temporaryFile->delete();
    }

    public function deleteUniverseThumbnail(string $serverId)
    {
        // Get the temporary file
        $temporaryFile = TemporaryFile::where('folder', $serverId)->first();

        if ($temporaryFile) {
            // Get full path of the file
            $fullPath = $temporaryFile->getFullPath('universe_thumbnail');

            if (Storage::disk('public')->exists($fullPath)) {
                Storage::disk('public')->delete($fullPath);
            }
        }

        // Delete the temporary file from the database
        $temporaryFile->delete();
    }

    public function deletePageImage(Request $request)
    {
        // return response()->json($request->all());

        // $name = "John Doe";
        // $response = [
        //     'name' => $name,
        //     'age' => rand(18, 60),
        //     'city' => ['New York', 'London', 'Paris', 'Tokyo'][rand(0, 3)],
        //     'hobbies' => ['reading', 'coding', 'traveling', 'painting'][rand(0, 3)],
        // ];

        // return response()->json($response);


        $serverId = $request->serverId;
        // If serverId is empty, return
        if ($serverId == '') {
            return;
        }

        // Get the temporary file
        $temporaryFile = TemporaryFile::where('folder', $serverId)->first();

        if ($temporaryFile) {
            // Get full path of the file
            $fullPath = $temporaryFile->getFullPath('pages');

            if (Storage::disk('public')->exists($fullPath)) {
                Storage::disk('public')->delete($fullPath);
            }
        }

        // Delete the temporary file from the database
        $temporaryFile->delete();
    }

    public function destroy(Request $request)
    {
        dd($request->all());
        // Get the temporary file
        // $temporaryFile = TemporaryFile::where('folder', $request->upload)->first();

        // if ($temporaryFile) {
        //     // Get full path of the file
        //     $fullPath = $temporaryFile->getFullPath($request->media);

        //     if (Storage::disk('public')->exists($fullPath)) {
        //         Storage::disk('public')->delete($fullPath);
        //     }
        // }

        // // Delete the temporary file from the database
        // $temporaryFile->delete();
    }
}
