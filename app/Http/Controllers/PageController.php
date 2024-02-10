<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Page;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //
    public function index(Chapter $chapter)
    {
        $pages = $chapter->pages()->paginate(1);
        return response()->json($pages);
    }

    public function getChapterPages(Chapter $chapter)
    {
        $pages = $chapter->pages()->get()->sortBy('page_number')->values()->all();

        // For every page, attach elements()
        foreach ($pages as $page) {
            $page->elements = $page->elements()->get();
        }

        // Sort pages by page number
        // $pages = $pages->sortBy('page_number');


        return response()->json($pages);
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'chapter_id' => 'required',
        ]);

        $page = $request->upload;

        $tempPage = TemporaryFile::where('folder', $page)->first();

        $chapter = Chapter::find($formFields['chapter_id']);

        $pageNumber = $chapter->pages()->max('page_number') + 1;

        $curPage = $chapter->pages()->create([
            'page_number' => $pageNumber,
            'page_image' => '',
        ]);


        if ($tempPage) {
            // Create a page

            // Add the page to the chapter
            $curPage->addMedia(storage_path('app/public/uploads/pages/tmp/' . $page . '/' . $tempPage->filename))
                ->toMediaCollection('page_image');

            $curPage->page_image = $curPage->getFirstMediaUrl('page_image');

            $curPage->save();

            rmdir(storage_path('app/public/uploads/pages/tmp/' . $page));
            $tempPage->delete();
        }

        return back();
    }

    public function update(Request $request, Page $page)
    {

        $tempThumbnail = TemporaryFile::where('folder', $request->upload)->first();

        if ($tempThumbnail) {
            $page->addMedia(storage_path('app/public/uploads/page_image/tmp/' . $tempThumbnail->folder . '/' . $tempThumbnail->filename))->toMediaCollection('page_image');

            rmdir(storage_path('app/public/uploads/page_image/tmp/' . $tempThumbnail->folder));

            $page->page_image = $page->getFirstMediaUrl('page_image');

            $tempThumbnail->delete();
        }


        $page->update();
        
        // Redirect back to the dashboard
        return redirect()->back();
    }

    public function destroy(Page $page)
    {
        $page->clearMediaCollection('page_image');
        $page->delete();

        return back();
    }

    public function getJsonPage($pageId)
    {
        $page = Page::find($pageId);

        return response()->json($page);
    }
}
