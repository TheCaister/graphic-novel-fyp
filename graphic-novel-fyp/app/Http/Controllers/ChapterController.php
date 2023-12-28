<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Series;
use App\Models\TemporaryFile;
use COM;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChapterController extends Controller
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
    public function create(Series $series)
    {
        // Get the universe of the series
        $universe = $series->universe;
        // Return the create page, passing in the series
        return Inertia::render(
            'Chapters/Create',
            [
                'passedUniverse' => $universe,
                'passedSeries' => $series,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd($request->all());

        $formFields = $request->validate([
            'series_id' => 'required',
            'chapter_title' => 'required',
            'chapter_notes' => 'required',
            'chapter_number' => 'required',
        ]);
      
        // From the series, get the highest chapter number. Then, add to it to get the next chapter number. If there are no chapters, set the chapter number to 1.
        // $nextChapterNumber = Chapter::where('series_id', $formFields['series_id'])->max('chapter_number') + 1;

        // $formFields['chapter_number'] = $nextChapterNumber;

        $tempThumbnail = TemporaryFile::where('folder', $request->upload)->first();

        // dd($request);

        $chapter = Chapter::create($formFields);

        if ($tempThumbnail) {
            $chapter->addMedia(storage_path('app/public/uploads/chapter_thumbnail/tmp/' . $request->upload . '/' . $tempThumbnail->filename))
                ->toMediaCollection('chapter_thumbnail');

            $chapter->chapter_thumbnail = $chapter->getFirstMediaUrl('chapter_thumbnail');

            $chapter->save();

            rmdir(storage_path('app/public/uploads/chapter_thumbnail/tmp/' . $request->upload));
            $tempThumbnail->delete();
        }

        // Go through each page in request->pages
        if ($request->pages) {
            $pageNumber = 1;

            foreach ($request->pages as $page) {
                // Get the temporary file
                $tempPage = TemporaryFile::where('folder', $page)->first();

                if ($tempPage) {
                    // Create a page
                    $curPage = $chapter->pages()->create([
                        'page_number' => $pageNumber,
                        'page_image' => '',
                    ]);

                    // Add the page to the chapter
                    $curPage->addMedia(storage_path('app/public/uploads/pages/tmp/' . $page . '/' . $tempPage->filename))
                        ->toMediaCollection('page_image');

                    $curPage->page_image = $curPage->getFirstMediaUrl('page_image');

                    $curPage->save();

                    $pageNumber++;

                    rmdir(storage_path('app/public/uploads/pages/tmp/' . $page));
                    $tempPage->delete();
                }
            }
        }



        // return redirect()->route('series.show', $chapter->series->series_id);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Chapter $chapter)
    {
        //
        return Inertia::render('Chapters/Show', [
            'series' => $chapter->series,
            'chapter' => $chapter,
            // 'series' => $chapter->series,
            // 'pages' => $chapter->pages,

            // paginate the pages
            'pages' => $chapter->pages()->paginate(1),

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapter $chapter)
    {
        //

        $chapterThumbnail = [];

        // dd($chapter->getFirstMediaUrl('chapter_thumbnail'));

        if ($chapter->chapter_thumbnail) {
            $filePath = $chapter->getFirstMediaUrl('chapter_thumbnail');

            $filePath = str_replace('http://localhost', '', $filePath);

            $chapterThumbnail = [
                [
                    'source' => $filePath,
                    'options' => [
                        'type' => 'local',
                    ],
                ]
            ];
        }

        // Initiliase array to store chapter pages. It has size of the number of pages in the chapter
        $chapterPagesToReturn = [];
        // Create array that holds all pages in the chapter, sorted by page number
        $pagesInOrder = $chapter->pages()->orderBy('page_number')->get();

        foreach ($pagesInOrder as $page) {
            $filePath = $page->getFirstMediaUrl('page_image');

            $filePath = str_replace('http://localhost', '', $filePath);

            // Add the page to the array
            $chapterPagesToReturn[] = [
                'source' => $filePath,
                'options' => [
                    'type' => 'local',
                ],
            ];
        }

        return Inertia::render('Chapters/Edit', [
            'passedSeries' => $chapter->series,
            'chapter' => $chapter,
            'passedChapterThumbnail' => $chapterThumbnail,
            'passedChapterPages' => $chapterPagesToReturn,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chapter $chapter)
    {
        //
        // dd($request->pages);

        // Get series id of the chapter
        $series_id = $chapter->series->series_id;

        $formFields = $request->validate([
            'chapter_title' => 'required',
            'chapter_notes' => 'required',
        ]);

        if ($request->upload == '') {
            $chapter->clearMediaCollection('chapter_thumbnail');

            // Set series thumbnail to null
            $chapter->chapter_thumbnail = '';
        }

        $tempThumbnail = TemporaryFile::where('folder', $request->upload)->first();

        if ($tempThumbnail) {
            $chapter->addMedia(storage_path('app/public/uploads/chapter_thumbnail/tmp/' . $tempThumbnail->folder . '/' . $tempThumbnail->filename))->toMediaCollection('chapter_thumbnail');

            rmdir(storage_path('app/public/uploads/chapter_thumbnail/tmp/' . $tempThumbnail->folder));

            // Set series thumbnail to the uploaded thumbnail
            $chapter->chapter_thumbnail = $chapter->getFirstMediaUrl('chapter_thumbnail');

            $tempThumbnail->delete();
        }

        $chapter->update($formFields);

   

        // Get all pages in the chapter
        $chapterPages = $chapter->pages;

        // Extract the "serverId" of each page in request->pages and store it in an array
        $requestPagesServerIdArray = [];

        foreach ($request->pages as $index=>$page) {
            $requestPagesServerIdArray[$index] = $page['serverId'];
        }

        // dd($requestPagesServerIdArray);

        // For each page in chapterPages, check if its source is in request->pages. If it is not, delete it. Otherwise, set its page number to the index of the page in request->pages + 1
        foreach ($chapterPages as $page) {
            $pageSource = $page->getFirstMediaUrl('page_image');

            $pageSource = str_replace('http://localhost', '', $pageSource);

            // dd($pageSource, $requestPagesServerIdArray);

            if (!in_array($pageSource, $requestPagesServerIdArray)) {
                // dd('page not found...');

                $page->clearMediaCollection('page_image');

                $page->delete();
            } else {

                $page->page_number = array_search($pageSource, $requestPagesServerIdArray) + 1;

                // Delete the page from request->pages. You can get the index by subtracting 1 from the page number
                unset($requestPagesServerIdArray[$page->page_number - 1]);

                $page->save();
            }
        }

        // Get the size of request->pages
        $requestPagesSize = count($requestPagesServerIdArray);

        // dd($requestPagesServerIdArray);

        // After this, all pages that weren't present in request->pages have been deleted. Now, we need to add the new pages to the chapter, with page number being the index of the page in request->pages + 1
        foreach ($requestPagesServerIdArray as $i => $serverId) {
            // dd($serverId);

            // Get the temporary file
            $tempPage = TemporaryFile::where('folder', $serverId)->first();

            if ($tempPage) {

                // Create a page
                $curPage = $chapter->pages()->create([
                    'page_number' => $i + 1,
                    'page_image' => '',
                ]);

                // Add the page to the chapter
                $curPage->addMedia(storage_path('app/public/uploads/pages/tmp/' . $tempPage->folder . '/' . $tempPage->filename))
                    ->toMediaCollection('page_image');

                $curPage->page_image = $curPage->getFirstMediaUrl('page_image');

                $curPage->save();

                rmdir(storage_path('app/public/uploads/pages/tmp/' . $tempPage->folder));
                $tempPage->delete();
            }
        }
        
        // dd all page images in the chapter
        // dd($chapter->pages->pluck('page_number'));



        return redirect()->route('chapter.manage', $series_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter)
    {
        $chapter->clearMediaCollection('chapter_thumbnail');

        foreach ($chapter->pages as $page) {
            $page->clearMediaCollection('page_image');
        }
        
        $series_id = $chapter->series->series_id;
        $chapter->delete();

        return redirect()->route('series.show', $series_id);
    }
}
