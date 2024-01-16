<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function editOther(Request $request, User $user): Response
    {
        // dd('hi');
        if (auth()->user()->is_admin) {
            // return view('profile.edit', [
            //     'user' => $user,
            // ]);

            return Inertia::render('Profile/Edit', [
                'user' => $user,
                // 'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
                // 'status' => session('status'),
            ]);
        } else {
            abort(403);
        }
    }

    public function show(User $user)
    {

        // Current user
        $user = User::all()->find($user->id);

        // Get all universe that the user owns
        $universes = $user->universes()->get();

        // Get all series that the user owns by looping through the universes
        $series = [];
        foreach ($universes as $universe) {
            $series = array_merge($series, $universe->series()->get()->toArray());
        }

        return Inertia::render('Profile/Components/Show', [
            'user' => $user,
            'universes' => $universes,
            'series' => $series,
        ]);
    }


    public function profile(Request $request)
    {
        // return Inertia::render('Profile/Show', [
        //     'user' => auth(),
        // ]);

        // Call the show method
        return $this->show(auth()->user());
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // dd($request->validated());

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function main()
    {
        // direct to dashboard
        return redirect()->route('dashboard');

        // return Inertia::render('Profile/Main', [
        //     'user' => auth()->user(),
        //     'passed_tab' => 'Dashboard',
        // ]);
    }

    public function dashboard()
    {
        return Inertia::render('Profile/Main', [
            'user' => auth()->user(),
            'passed_tab' => 'Dashboard',
        ]);
    }

    public function showMain(){
        return Inertia::render('Profile/Main', [
            'user' => auth()->user(),
            'passed_tab' => 'Show',
        ]);
    }

    public function editMain(){
        return Inertia::render('Profile/Main', [
            'user' => auth()->user(),
            'passed_tab' => 'Edit',
        ]);
    }
}
