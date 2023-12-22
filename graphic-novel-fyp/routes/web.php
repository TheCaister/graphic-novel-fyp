<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ElementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\UniverseController;
use App\Http\Controllers\UploadController;
use App\Models\Series;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/tailwind', function () {
    return Inertia::render('TailwindPractice');
});

Route::post('forgot-password', [RegisteredUserController::class, 'resetPassword'])
->name('user.resetPassword');

Route::get('/', function () {
    // Instead of returning Blade view, you're returning client-side view
    // The base directory is assumed to be resources/js/Pages

    // When propping, make sure to accept it in the vue component
    return Inertia::render(
        'Welcome',
        [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]
    );
})->name('home');

// Using group, you can apply middleware to all routes in the group
Route::middleware('auth')->group(function () {

 

    // TEST ROUTES ////////////////////////

    Route::get('/users', function () {
        // sleep(2);
        return Inertia::render('Users/Index', [
            'time' => now()->toTimeString(),
            'users' => User::query()
                // Only run this function if the search query is present in the request
                // With {}, you can use the $search variable
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                    // dd($search);
                })
                ->paginate(10, ['id', 'name'])
                // withQueryString will keep the query string in the URL
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'can' => [
                        'edit' => Auth::user()->can('edit', $user),
                    ]
                ]),
            // 'users' => User::all(),
            'filters' => Request::only(['search']),

            // On the Vue side, you can access this with can.createUsers
            // A good way to protect is by creating a policy
            // php artisan make:policy UserPolicy --model=User
            'can' => [
                'createUser' => Auth::user()->can('create', User::class),
            ]
        ]);
    });

    Route::get('users/create', function () {
        return Inertia::render('Users/Create');
    })->can('create', 'App/Models/User');

    // Create a post request to create a new user
    Route::post('users', function () {

        $attributes = request()->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],

        ]);

        User::create($attributes);


        return redirect('/users');
    });

    Route::get('/home', function () {
        return Inertia::render('Home');
    });

    Route::post('/testpost', function () {
        // dd('Testing post request...');
        dd(request()->all());
        // dump(request('foo'));
    });

    //////////////////////////////////////

    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->middleware(['auth', 'verified'])->name('dashboard');

    // Route::middleware('auth')->group(function () {
    //     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/profile/{user}/edit', [ProfileController::class, 'editOther'])->name('profile.editOther');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/{user}/personal', [ProfileController::class, 'updateOtherPersonal'])->name('profile.updateOtherPersonal');
    Route::patch('/profile/personal', [ProfileController::class, 'updatePersonal'])->name('profile.updatePersonal');
    Route::patch('/profile/{user}', [ProfileController::class, 'updateOther'])->name('profile.updateOther');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/users/{user}', [ProfileController::class, 'destroyOther']);
    
    Route::get('/user/main', [ProfileController::class, 'main'])->name('user.main');
    Route::get('/user/main/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

    // Route::get('/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('user/main/show', [ProfileController::class, 'showMain'])->name('user.main.show');
    Route::get('user/main/edit', [ProfileController::class, 'editMain'])->name('user.main.edit');
});


Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');

Route::get('/publish', [SeriesController::class, 'publish'])->name('publish');

// Trying out resource
Route::resource('universes', UniverseController::class);

//////////////////////// SERIES ROUTES ////////////////////////
Route::get('{series}/chapters/create', [ChapterController::class, 'create'])->name('chapter.create');
Route::get('{series}/chapters/manage', [SeriesController::class, 'manageChapters'])->name('chapter.manage');
Route::resource('chapters', ChapterController::class);

Route::get('{universe}/series/create', [SeriesController::class, 'create'])->name('series.create');
Route::get('/series/genres',[SeriesController::class, 'genres'])->name('series.genres');
Route::resource('series', SeriesController::class);

////////////////////////// ELEMENT ROUTES ////////////////////////
Route::get('user/main/elementsforge', [ElementController::class, 'elementsforge'])->name('user.main.elementsforge');

Route::get('/elements/assign', [ElementController::class, 'assign'])->name('elements.assign');

Route::post('/elements/assign', [ElementController::class, 'assignStore'])->name('elements.assignStore');

Route::resource('elements', ElementController::class);

////////////////////////// FORGE ROUTES ////////////////////////
Route::get('/forge/show', [ElementController::class, 'forgeShow'])->name('forge.show');

////////////////////////// MEDIA ROUTES ////////////////////////
// http://localhost/upload?media=series_thumbnail 

require __DIR__ . '/auth.php';
