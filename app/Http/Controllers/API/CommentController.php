<?php

namespace App\Http\Controllers\API;

use App\Models\API\Post;
use App\Models\API\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        return response([
            'comments' => $post->comments()->with('user:id,name')->get()
        ], 200);
    }

    // create a comment
    public function store(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);

        Comment::create([
            'comment' => $attrs['comment'],
            'post_id' => $id,
            'user_id' => auth('sanctum')->user()->id
        ]);

        return response([
            'message' => 'Comment created.'
        ], 200);
    }


     // update a comment
     public function update(Request $request, $id)
     {
         $comment = Comment::find($id);

         if(!$comment)
         {
             return response([
                 'message' => 'Comment not found.'
             ], 403);
         }

         if($comment->user_id != auth('sanctum')->user()->id)
         {
             return response([
                 'message' => 'Permission denied.'
             ], 403);
         }

         //validate fields
         $attrs = $request->validate([
             'comment' => 'required|string'
         ]);

         $comment->update([
             'comment' => $attrs['comment']
         ]);

         return response([
             'message' => 'Comment updated.'
         ], 200);
     }

    // delete a comment
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response([
                'message' => 'Comment not found.'
            ], 403);
        }

        if($comment->user_id != auth('sanctum')->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $comment->delete();

        return response([
            'message' => 'Comment deleted.'
        ], 200);
    }
}
