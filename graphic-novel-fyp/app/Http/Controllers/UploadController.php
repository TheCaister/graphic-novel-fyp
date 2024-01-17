<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Page;
use App\Models\Series;
use App\Models\TemporaryFile;
use App\Models\Universe;
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
                $fullPath = $temporaryFile->getFullPath($this->getMediaCollectionName(request()->contentType));

                if (Storage::disk('public')->exists($fullPath)) {
                    Storage::disk('public')->delete($fullPath);
                }
            }

            $temporaryFile->delete();
        }
    }

    private function getMediaCollectionName($contentType)
    {
        switch ($contentType) {
            case 'universe':
                return 'universe_thumbnail';
                break;
            case 'series':
                return 'series_thumbnail';
                break;
            case 'chapter':
                return 'chapter_thumbnail';
                break;
            case 'page':
                return 'page_image';
                break;
            default:
                break;
        }
    }

    private function clearMediaCollection($contentType, $contentId)
    {
        // Do a switch on contentType
        switch ($contentType) {
            case 'universe':
                $universe = Universe::where('universe_id', $contentId)->first();
                $universe->clearMediaCollection('universe_thumbnail');
                $universe->save();
                break;
            case 'series':
                $series = Series::where('series_id', $contentId)->first();
                $series->clearMediaCollection('series_thumbnail');
                $series->save();
                break;
            case 'chapter':
                $chapter = Chapter::where('chapter_id', $contentId)->first();
                $chapter->clearMediaCollection('chapter_thumbnail');
                $chapter->save();
                break;
            case 'page':
                $page = Page::where('page_id', $contentId)->first();
                $page->clearMediaCollection('page_image');
                $page->save();
                break;
            default:
                break;
        }
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
}
