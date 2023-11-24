<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    //
    function getFollowings(Request $request) {
        // Get user from request user_id
        $user = User::find($request->user_id);
        $followings = $user->followees()->get();

        // For each following, attach the number of series they have and the number of followers they have
        foreach ($followings as $following) {
            // Get the number of series the following has by going through each universe and counting the number of series
            $following->series_count = 0;
            foreach ($following->universes()->get() as $universe) {
                $following->series_count += $universe->series()->count();
            }
            $following->followers_count = $following->followers()->count();
        }

        return response()->json($followings);
    }
}
