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

    public function deleteThumbnail()
    {
        if (request()->isTemp == 'false') {
            $this->clearMediaCollection(request()->contentType, request()->contentId);
        } else {
            $temporaryFile = TemporaryFile::where('folder', request()->serverId)->first();

            if ($temporaryFile) {
                $fullPath = $temporaryFile->getFullPath($this->getClassName(request()->contentType)::getThumbnailCollectionName());

                if (Storage::disk('public')->exists($fullPath)) {
                    Storage::disk('public')->delete($fullPath);
                }
            }

            $temporaryFile->delete();
        }
    }

    private function clearMediaCollection($contentType, $contentId)
    {
        $content = $this->getClassName($contentType)::find($contentId);
        $content->clearMediaCollection($this->getClassName($contentType)::getThumbnailCollectionName());
        $content->save();
    }

    public function deletePageImage(Request $request)
    {

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

    private function getClassName($contentType)
    {
        return 'App\\Models\\' . $contentType;
    }
}
