<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * @test
     */
    public function get_posts_success()
    {
        $user = User::find(1);
        $response = $this->actingAs($user, 'api')
            ->get('api/posts', ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'current_page',
                'data',
            ]
        ]);
    }

    /**
     * @test
     */
    public function get_posts_fail()
    {
        $response = $this->get('api/posts', ['Accept' => 'application/json']);
        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function create_post()
    {
        $user = User::find(1);
        $response = $this->actingAs($user, 'api')
            ->post('api/posts', [
                    'body' => 'The test post content goes here.'
                ]
                , ['Accept' => 'application/json']);

        $response->assertStatus(200);

    }

    /**
     * @test
     */
    public function create_post_fail()
    {
        $user = User::find(1);
        $response = $this->actingAs($user, 'api')
            ->post('api/posts', [
                    'body' => 'The test'
                ]
                , ['Accept' => 'application/json']);

        $response->assertStatus(422);

    }

    /**
     * @test
     */
    public function approve_post_fail()
    {
        $user = User::find(2);
        $response = $this->actingAs($user, 'api')
            ->put('api/posts/1/approve', [], ['Accept' => 'application/json']);

        $response->assertStatus(403);

    }

    /**
     * @test
     */
    public function approve_post_success()
    {
        $user = User::find(1);
        $user->refresh();
        $response = $this->actingAs($user, 'api')
            ->put('api/posts/1/approve', [], ['Accept' => 'application/json']);

        $response->assertStatus(200);

    }


    /**
     * @test
     */
    public function reject_post_fail()
    {
        $user = User::find(2);
        $response = $this->actingAs($user, 'api')
            ->put('api/posts/1/reject', [], ['Accept' => 'application/json']);

        $response->assertStatus(403);

    }

    /**
     * @test
     */
    public function reject_post_success()
    {
        $user = User::find(1);
        $user->refresh();
        $response = $this->actingAs($user, 'api')
            ->put('api/posts/1/reject', [], ['Accept' => 'application/json']);

        $response->assertStatus(200);

    }

    /**
     * @test
     */
    public function delete_post_success()
    {
        $user = User::find(1);
        $user->refresh();
        $response = $this->actingAs($user, 'api')
            ->delete('api/posts/1', [], ['Accept' => 'application/json']);

        $response->assertStatus(200);

    }

    /**
     * @test
     */
    public function delete_post_user_success()
    {
        $post = Post::first();
        $user = User::find($post->user_id);

        $user->refresh();
        $response = $this->actingAs($user, 'api')
            ->delete('api/posts/' . $post->id, [], ['Accept' => 'application/json']);

        $response->assertStatus(200);

    }

    /**
     * @test
     */
    public function delete_post_user_fail()
    {
        $post = Post::first();
        $user = User::where('id', '!=', $post->user_id)->where('role', User::ROLE_USER)->first();

        $user->refresh();
        $response = $this->actingAs($user, 'api')
            ->delete('api/posts/' . $post->id, [], ['Accept' => 'application/json']);

        $response->assertStatus(403);

    }

    /**
     * @test
     */
    public function comments_all_success()
    {
        $post = Post::first();
        $user = User::find($post->user_id);
        $response = $this->actingAs($user, 'api')
            ->get("api/posts/{$post->id}/comments/" , ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
            ]
        ]);
    }

    /**
     * @test
     */
    public function create_comments_success()
    {
        $post = Post::first();
        $user = User::find($post->user_id);
        $response = $this->actingAs($user, 'api')
            ->post("api/posts/{$post->id}/comments/" , [
                'post_id' => $post->id,
                'body' => 'This is a test comment.'
            ],['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
            ]
        ]);
    }

    /**
     * @test
     */
    public function create_comments_fail()
    {
        $post = Post::first();
        $user = User::find($post->user_id);
        $response = $this->actingAs($user, 'api')
            ->post("api/posts/{$post->id}/comments/" , [
                'post_id' => $post->id,
                'body' => 'This is '
            ],['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => [
                'body'
            ]
        ]);
    }
}
