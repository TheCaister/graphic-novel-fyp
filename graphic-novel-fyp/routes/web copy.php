<?php

use App\Http\Controllers\ProfileController;
use App\Models\Series;
use App\Models\User;
use Illuminate\Foundation\Application;
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

// Using group, you can apply middleware to all routes in the group
Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        // Instead of returning Blade view, you're returning client-side view
        // The base directory is assumed to be resources/js/Pages

        // When propping, make sure to accept it in the vue component
        return Inertia::render(
            'Welcome',
            [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
                // 'name' => 'John Doe',
                'name' => Auth::user()->username,
                'series' => Series::all(),
            ]
        );


    });

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

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';