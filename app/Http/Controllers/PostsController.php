<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostApproveRequest;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostDeleteRequest;
use App\Http\Requests\PostRejectRequest;
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
        try {
            $term = $request->input('term', null);
            $userId = $request->input('userId', null);
            $filter = $request->input('filter', null);

            $posts = Post::with('user')->latest();

            $posts = request()->user()->role === User::ROLE_ADMIN ? $posts->approveAndPending() : $posts->approve();

            if ($filter == Post::FILTER_PENDING_POSTS && request()->user()->role === User::ROLE_ADMIN) {
                $posts = $posts->pending();
            } else {
                $posts = $posts->approve();
            }

            $posts = $userId ? $posts->where('user_id', $userId) : $posts;

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
        } catch (Exception $e) {
            exception_logger($e);
            return response()->json(['message' => __('message.something_went_wrong')], 500);
        }
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

            $post = Post::create($data);
            return response()->json(['data' => $post]);
        } catch (Exception $e) {
            return response()->json(['message' => __('message.something_went_wrong')], 500);
        }
    }

    /**
     * Approve post
     *
     * @param PostApproveRequest $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(PostApproveRequest $request, Post $post)
    {
        try {
            $post->update(['status' => Post::STATUS_APPROVE]);
            return response()->json(['data' => $post]);
        } catch (Exception $e) {
            exception_logger($e);
            return response()->json(['message' => __('message.something_went_wrong')], 500);
        }
    }

    /**
     * Reject post
     *
     * @param PostRejectRequest $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(PostRejectRequest $request, Post $post)
    {
        try {
            $post->update(['status' => Post::STATUS_REJECT]);
            return response()->json(['data' => $post]);
        } catch (Exception $e) {
            exception_logger($e);
            return response()->json(['message' => __('message.something_went_wrong')], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PostDeleteRequest $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PostDeleteRequest $request, Post $post)
    {
        try {
            return response()->json([
                'message' => 'Successfully deleted.',
                'status' => $post->delete(),
            ]);
        } catch (Exception $e) {
            exception_logger($e);
            return response()->json(['message' => __('message.something_went_wrong')], 500);
        }
    }
}
