<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Js;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Create a new comment, using commentable_type and commentable_id
        $comment = Comment::create([
            'commenter_id' => $request->commenter_id,
            'commentable_type' => $request->commentable_type,
            'commentable_id' => $request->commentable_id,
            'comment_content' => $request->comment_content,
        ]);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getComments(Request $request)
    {
        // Initialise the comments variable
        $comments = null;

        // Check if the request has a user_id
        if ($request->has('user_id')) {
            // Get comments, with the latest comments first

            $comments = $this->getUserComments($request->user_id);
        } else if ($request->has('commentable_id')) {
            $comments = $this->getCommentableComments($request->commentable_id, $request->commentable_type);

            // Reverse the comments so that the latest comments are at the bottom

        }

        if ($request->attach_replies) {
            // Filter out comments that are replies
            $comments = $comments->filter(function ($comment) {
                return $comment->replying_to == null;
            });

            // Attach replies to the comments
            $comments = $comments->map(function ($comment) {
                $comment->replies = $comment->replies()->get();
                return $comment;
            });
        }

        return response()->json([
            'comments' => $comments->values(),
        ]);
    }

    // getUserComments() returns the comments made by a user, passing in the user_id as a parameter
    public function getUserComments(string $user_id)
    {
        // Get the comments from the database, attach an is_reply attribute and commentable_name attribute. For the commentable_type attribute, remove the App\Models\ prefix. Generate a commentable_link attribute based on the commentable_type and commentable_id attributes.
        $comments = Comment::where('commenter_id', $user_id)->with('commentable')->get()->map(function ($comment) {
            $comment->is_reply = $comment->replying_to != null;
            $comment->commentable_name = $comment->commentable->series_title ?? $comment->commentable->chapter_title;
            $comment->commentable_type = str_replace('App\Models\\', '', $comment->commentable_type);

            // If chapters, generate a link to the chapter page. If series, generate a link to the series page.
            if ($comment->commentable_type == 'Chapter') {
                $comment->commentable_link = '/chapters/' . $comment->commentable_id;
            } else {
                $comment->commentable_link = '/series/' . $comment->commentable_id;
            }

            return $comment;
        });

        return $comments;
    }

    public function getCommentableComments(string $commentable_id, string $commentable_type)
    {
        $commentable_type = 'App\Models\\' . ucfirst($commentable_type);

        $comments = Comment::where('commentable_id', $commentable_id)->where('commentable_type', $commentable_type)->with('commenter')->get();

        return $comments;
    }
}
