<?php

use App\Http\Controllers\PageController;
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
Route::get('/universes/{user}', [App\Http\Controllers\UniverseController::class, 'getUniverses'])->name('universes.getUniverses');

