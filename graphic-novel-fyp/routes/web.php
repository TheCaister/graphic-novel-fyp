<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Foundation\Application;
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
            'name' => 'John Doe',
            'frameworks' => [
                'Laravel',
                'React',
                'Vue',
                'Angular',
                'Tailwind'
            ],
        ]
    );
});

// TEST ROUTES ////////////////////////

Route::get('/users', function () {
    // sleep(2);
    return Inertia::render('Users', [
        'time' => now()->toTimeString(),
        'users' => User::all(),
    ]);
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

require __DIR__ . '/auth.php';
