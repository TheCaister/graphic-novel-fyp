<?php

use App\Http\Controllers\SeriesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/chapters/{chapter}/pages', [PageController::class, 'index'])->name('pages.index');

// Api call to get all universes owned by the user. The function is called getUniverses
Route::get('/universes', [App\Http\Controllers\UniverseController::class, 'getUniverses'])->name('universes.get-universes');

Route::get('/chapters/{chapter}/pages', [App\Http\Controllers\PageController::class, 'getChapterPages'])->name('pages.chapter-pages');

Route::get('/series/genres', [App\Http\Controllers\SeriesController::class, 'getGenreSeries'])->name('series.get-genre-series');

Route::get('/series/recent', [App\Http\Controllers\SeriesController::class, 'getRecentSeries'])->name('series.get-recent-series');

Route::get('/series/{series}/chapters', [App\Http\Controllers\SeriesController::class, 'getSeriesChapters'])->name('chapters.get-series-chapters');

Route::get('/series/{universe}', [App\Http\Controllers\SeriesController::class, 'getSeries'])->name('series.get-series');







Route::get('/genres', [SeriesController::class, 'getGenres'])->name('series.get-genres');

Route::get('/elements', [App\Http\Controllers\ElementController::class, 'getElements'])->name('elements.get-elements');

Route::get('/content/get-parent', function (Request $request) {

    $content_type = $request->type;
    $content_id = $request->id;
    $result = [
        'view' => null,
        'parentid' => null
    ];

    // Switch statement to determine which model to use
    // For example, if we're currently on SeriesView, then the parent model is a Universe
    switch ($content_type) {
        case 'series':
            $model = App\Models\Series::find($content_id);
            $parent = $model->universe;

            $result['view'] = 'UniverseView';
            $result['parentid'] = $parent->universe_id;
            break;
        case 'chapter':
            $model = App\Models\Chapter::find($content_id);
            $parent = $model->series;

            $result['view'] = 'SeriesView';
            $result['parentid'] = $model->series_id;
            break;
        case 'page':
            $model = App\Models\Page::find($content_id);
            $parent = $model->chapter;

            $result['view'] = 'ChapterView';
            $result['parentid'] = $model->chapter_id;
            break;
        default:
            $result['view'] = 'UniverseView';
            $result['parentid'] = null;
            break;
    }

    // Return the parent model as a json response
    return response()->json($result);
    
})->name('content.get-parent');

Route::delete('/series/{serverId}/thumbnail', [App\Http\Controllers\UploadController::class, 'deleteSeriesThumbnail'])->name('series.delete-thumbnail');

Route::delete('/pages/{serverId}', [App\Http\Controllers\UploadController::class, 'deletePageImage'])->name('pages.delete-image');