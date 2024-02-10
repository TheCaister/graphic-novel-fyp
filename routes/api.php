<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\UniverseController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\returnSelf;

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

// Route::get('/chapters/{chapter}/pages', [App\Http\Controllers\PageController::class, 'getChapterPages'])->name('pages.chapter-pages');

Route::get('/series/genres', [App\Http\Controllers\SeriesController::class, 'getGenreSeries'])->name('series.get-genre-series');

Route::get('/series/recent', [App\Http\Controllers\SeriesController::class, 'getRecentSeries'])->name('series.get-recent-series');

Route::get('/recents', [SearchController::class, 'getRecent'])->name('search.get-recent');

Route::get('/series/{series}/latest-number', [SeriesController::class, 'getLatestChapterNumber'])->name('series.get-latest-number');

Route::get('/series/{series}/chapters', [App\Http\Controllers\SeriesController::class, 'getSeriesChapters'])->name('chapters.get-series-chapters');

Route::get('/series/{universe}', [App\Http\Controllers\SeriesController::class, 'getSeries'])->name('series.get-series');

Route::get('chapters/{chapter}/pages', [PageController::class, 'getChapterPages'])->name('pages.get-chapter-pages');

Route::get('/chapters/{chapter}/filepond-pages', [ChapterController::class, 'getChapterFilepondPages'])->name('chapters.get-chapter-filepond-pages');

Route::get('/search', [SearchController::class, 'searchJson'])->name('search.json');

Route::get('/search/mention', [SearchController::class, 'searchMention'])->name('search.mention');


Route::get('/genres', [SeriesController::class, 'getGenres'])->name('series.get-genres');

Route::get('/elements/assigned', [SearchController::class, 'getAssignedElements'])->name('elements.get-assigned-elements');

Route::get('/elements', [App\Http\Controllers\ElementController::class, 'getElements'])->name('elements.get-elements');

Route::delete('/thumbnail', [UploadController::class, 'deleteThumbnail'])->name('delete-thumbnail');

Route::delete('/universes/{serverId}/thumbnail', [App\Http\Controllers\UploadController::class, 'deleteUniverseThumbnail'])->name('universes.delete-thumbnail');

Route::delete('/chapters/{serverId}/thumbnail', [App\Http\Controllers\UploadController::class, 'deleteChapterThumbnail'])->name('chapters.delete-thumbnail');

Route::delete('/series/{serverId}/thumbnail', [App\Http\Controllers\UploadController::class, 'deleteSeriesThumbnail'])->name('series.delete-thumbnail');

Route::delete('/pages/{serverId}', [App\Http\Controllers\UploadController::class, 'deletePageImage'])->name('pages.delete-image');

Route::delete('images/{serverId}', [App\Http\Controllers\UploadController::class, 'deleteImage'])->name('images.delete-image');

Route::group([], function () {
    Route::get('universes/{universe}', [UniverseController::class, 'getJsonUniverse'])->name('universes.get-universe-json');
    Route::get('series/{series}', [SeriesController::class, 'getJsonSeries'])->name('series.get-series-json');
    Route::get('chapters/{chapter}', [SeriesController::class, 'getJsonChapter'])->name('chapters.get-chapter-json');
    Route::get('pages/{page}', [PageController::class, 'getJsonPage'])->name('pages.get-page-json');
    Route::middleware('auth:sanctum')->get('content/get-siblings', [SearchController::class, 'getSiblingContent'])->name('content.get-siblings');
});

Route::group([], function () {
    Route::get('universes/{universe}/thumbnail', [UniverseController::class, 'getThumbnailJson'])->name('universes.get-thumbnail-json');
});


