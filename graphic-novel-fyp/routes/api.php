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

Route::get('/series/popular', [App\Http\Controllers\SeriesController::class, 'getPopularSeries'])->name('series.get-popular-series');

Route::get('/series/genres', [App\Http\Controllers\SeriesController::class, 'getGenreSeries'])->name('series.get-genre-series');

Route::get('/series/recent', [App\Http\Controllers\SeriesController::class, 'getRecentSeries'])->name('series.get-recent-series');

Route::get('/series/{universe}', [App\Http\Controllers\SeriesController::class, 'getSeries'])->name('series.get-series');

Route::get('/genres', [SeriesController::class, 'getGenres'])->name('series.get-genres');

Route::get('/comments', [App\Http\Controllers\CommentController::class, 'getComments'])->name('comments.get-comments');

Route::get('/followings', [App\Http\Controllers\FollowerController::class, 'getFollowings'])->name('followings.get-followings');

Route::get('/elements', [App\Http\Controllers\ElementController::class, 'getElements'])->name('elements.get-elements');

Route::post('/series/rate', [App\Http\Controllers\SeriesController::class, 'rateSeries'])->name('series.rate');

Route::delete('/series/{serverId}/thumbnail', [App\Http\Controllers\UploadController::class, 'deleteSeriesThumbnail'])->name('series.delete-thumbnail');

Route::delete('/pages/{serverId}', [App\Http\Controllers\UploadController::class, 'deletePageImage'])->name('pages.delete-image');