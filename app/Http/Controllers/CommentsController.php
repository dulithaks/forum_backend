<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Exception;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Post $post)
    {
        try {
            return Comment::with('user')
                ->where('post_id', $post->id)
                ->latest()
                ->paginate($request->input('take', 3));
        }
        catch(Exception $e) {
            exception_logger($e, $request->all());
            return response()->json(['message' => __('message.something_went_wrong')], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = request()->user()->id;

            $comment =  Comment::create($data);
            return response()->json(['data' => $comment]);
        }
        catch (Exception $e) {
            exception_logger($e);
            return response()->json(['message' => __('message.something_went_wrong')], 500);
        }
    }
}
