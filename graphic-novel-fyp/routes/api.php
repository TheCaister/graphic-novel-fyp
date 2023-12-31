<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\SeriesController;
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

Route::get('/series/{series}/chapters', [App\Http\Controllers\SeriesController::class, 'getSeriesChapters'])->name('chapters.get-series-chapters');

Route::get('/series/{universe}', [App\Http\Controllers\SeriesController::class, 'getSeries'])->name('series.get-series');

Route::get('chapters/{chapter}/pages', [PageController::class, 'getChapterPages'])->name('pages.get-chapter-pages');







Route::get('/genres', [SeriesController::class, 'getGenres'])->name('series.get-genres');

Route::get('/elements', [App\Http\Controllers\ElementController::class, 'getElements'])->name('elements.get-elements');

Route::delete('/universes/{serverId}/thumbnail', [App\Http\Controllers\UploadController::class, 'deleteUniverseThumbnail'])->name('universes.delete-thumbnail');

Route::delete('/chapters/{serverId}/thumbnail', [App\Http\Controllers\UploadController::class, 'deleteChapterThumbnail'])->name('chapters.delete-thumbnail');

Route::delete('/series/{serverId}/thumbnail', [App\Http\Controllers\UploadController::class, 'deleteSeriesThumbnail'])->name('series.delete-thumbnail');

Route::delete('/pages/{serverId}', [App\Http\Controllers\UploadController::class, 'deletePageImage'])->name('pages.delete-image');

// Route::delete('/pages', [App\Http\Controllers\UploadController::class, 'deletePageImage'])->name('pages.delete-image');