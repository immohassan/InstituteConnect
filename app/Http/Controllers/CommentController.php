<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $user = Auth::user();
        
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = $user->id;
        $comment->post_id = $post->id;
        $comment->save();
        
        // Create notification for post owner (if it's not the same user)
        if ($post->user_id != $user->id) {
            $notification = new Notification();
            $notification->user_id = $post->user_id;
            $notification->type = 'comment';
            $notification->data = json_encode([
                'post_id' => $post->id,
                'comment_id' => $comment->id,
                'sender_id' => $user->id,
                'sender_name' => $user->name,
            ]);
            $notification->save();
        }
        
        if ($request->ajax()) {
            $comment->load('user');
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'user' => [
                    'name' => $comment->user->name,
                    'avatar' => $comment->user->avatar,
                ],
                'created_at' => $comment->created_at->diffForHumans(),
            ]);
        }
        
        return redirect()->route('posts.show', $post->id)
            ->with('success', 'Comment added successfully!')
            ->withFragment('comment-' . $comment->id);
    }

    /**
     * Display the specified comment.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return redirect()->route('posts.show', $comment->post_id)
            ->withFragment('comment-' . $comment->id);
    }

    /**
     * Show the form for editing the specified comment.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        // Check if the user is the comment creator
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            return redirect()->route('posts.show', $comment->post_id)
                ->with('error', 'You do not have permission to edit this comment.');
        }
        
        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        // Check if the user is the comment creator
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            return redirect()->route('posts.show', $comment->post_id)
                ->with('error', 'You do not have permission to edit this comment.');
        }
        
        $request->validate([
            'content' => 'required|string',
        ]);
        
        $comment->content = $request->content;
        $comment->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'content' => $comment->content,
                'updated_at' => $comment->updated_at->diffForHumans(),
            ]);
        }
        
        return redirect()->route('posts.show', $comment->post_id)
            ->with('success', 'Comment updated successfully!')
            ->withFragment('comment-' . $comment->id);
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        // Check if the user is the comment creator or an admin
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            return redirect()->route('posts.show', $comment->post_id)
                ->with('error', 'You do not have permission to delete this comment.');
        }
        
        $postId = $comment->post_id;
        
        // Delete any likes associated with this comment
        $comment->likes()->delete();
        
        // Delete the comment
        $comment->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully!',
            ]);
        }
        
        return redirect()->route('posts.show', $postId)
            ->with('success', 'Comment deleted successfully!');
    }
    
    /**
     * Like or unlike a comment.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function like(Comment $comment)
    {
        $user = Auth::user();
        $like = $comment->likes()->where('user_id', $user->id)->first();
        
        if ($like) {
            // Unlike the comment
            $like->delete();
            $message = 'Comment unliked.';
        } else {
            // Like the comment
            $like = new \App\Models\Like();
            $like->user_id = $user->id;
            $like->likeable_id = $comment->id;
            $like->likeable_type = Comment::class;
            $like->save();
            $message = 'Comment liked!';
            
            // Create notification for comment owner (if it's not the same user)
            if ($comment->user_id != $user->id) {
                $notification = new Notification();
                $notification->user_id = $comment->user_id;
                $notification->type = 'like';
                $notification->data = json_encode([
                    'post_id' => $comment->post_id,
                    'comment_id' => $comment->id,
                    'sender_id' => $user->id,
                    'sender_name' => $user->name,
                    'content_type' => 'comment',
                ]);
                $notification->save();
            }
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'likes_count' => $comment->likes()->count()
            ]);
        }
        
        return redirect()->route('posts.show', $comment->post_id)
            ->with('success', $message)
            ->withFragment('comment-' . $comment->id);
    }
}