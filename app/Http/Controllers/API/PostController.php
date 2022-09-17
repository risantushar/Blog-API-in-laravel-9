<?php

namespace App\Http\Controllers\API;

use App\Models\API\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    // get all posts
    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'desc')
            ->with('user:id,name')
            ->withCount('comments', 'likes')
            ->with('likes', function($like){
                return $like->where('user_id', auth('sanctum')->user()->id)
                    ->select('id', 'user_id', 'post_id')->get();
            })
            ->get()
        ], 200);
    }

    public function show($id)
    {
        return response([
            'post' => Post::where('id', $id)->withCount('comments', 'likes')->get()
        ], 200);
    }

    public function store(Request $request)
    {
        $attrs = $request->validate([
            'desc' => 'required|string'
        ]);

        $image = null;

        $post = DB::table('posts')->insert([
            'desc' => $request->desc,
            'user_id' => auth('sanctum')->user()->id,
            'photo' => $image
        ]);

        // for now skip for post image

        return response([
            'message' => 'Post created.',
            'post' => $post,
        ], 200);

    }

    public function update(Request $request,$id)
    {

        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        if($post->user_id != auth('sanctum')->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'desc' => 'required|string'
        ]);

        $updatedPost = DB::table('posts')
        ->where('id',$id)
        ->update([
            'desc' => $request->desc
        ]);

          // for now skip for post image

          return response([
            'message' => 'Post updated.',
            'post' => $updatedPost
        ], 200);
    }

    public function delete($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        if($post->user_id != auth('sanctum')->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response([
            'message' => 'Post deleted.'
        ], 200);
    }
}
