<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    //
    public function store(Request $request){

        // Check if $request->media is set. If not, return an empty string
        $media = $request->media ?? '';
        
        if($media == ''){
            return '';
        }
        
        if($request->hasFile('upload')){
            $file = $request->file('upload');
            $filename = $file->getClientOriginalName();
            $folder = uniqid() . '-' . now()->timestamp;

            $file->storeAs('/uploads/'.$media.'/tmp/'.$folder, $filename, 'public');

            // $file->storeAs('/uploads/chapter_thumbnail/tmp/'.$folder, $filename, 'public');

            // Store the folder and filename in the database

            TemporaryFile::create([
                'folder' => $folder,
                'filename' => $filename,
            ]);
            
            return $folder;
        }

        return '';
    }
}
