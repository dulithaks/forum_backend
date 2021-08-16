<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostApproveRequest;
use App\Http\Requests\PostCreateRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $term = $request->input('term', null);
        $posts = Post::with('user')->latest();

        if (request()->user()->role === User::ROLE_ADMIN) {
            $posts = $posts->approveAndPending();
        }
        else {
            $posts = $posts->approve();
        }

        if ($term) {
            $posts = $posts->where(function ($q) use ($term) {
                $q->where('body', 'like', "%{$term}%")
                    ->orWhereHas('user', function ($q) use ($term) {
                        $q->where('first_name', 'like', "%{$term}%")
                            ->orWhere('last_name', 'like', "%{$term}%");
                    });
            });
        }

        return $posts->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PostCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = request()->user()->id;
            $data['status'] = request()->user()->role == User::ROLE_ADMIN ? 1 : 0;

            $post =  Post::create($data);
            return response()->json(['data' => $post]);
        }
        catch (Exception $e) {
            return response()->json(['message' => __('message.something_went_wrong')], 500);
        }
    }

    /**
     * Approve a post
     *
     * @param PostApproveRequest $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(PostApproveRequest $request, Post $post)
    {
        $post->update(['status' => Post::STATUS_APPROVE]);
        return response()->json(['data' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
