<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Society;
use App\Models\Notification;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
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
     * Display a listing of the posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get societies the user is a member of
        $societyIds = $user->societies->pluck('id')->toArray();
        
        // Get posts from these societies and general posts (no society_id)
        // $posts = Post::query()
        //     ->with(['user', 'society', 'comments', 'likes'])
        //     ->where(function($query) use ($societyIds) {
        //         $query->whereIn('society_id', $societyIds)
        //               ->orWhereNull('society_id');
        //     })
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(10);
        $posts = Post::where(function($query) use ($societyIds) {
                    $query->whereIn('society_id', $societyIds)
                        ->orWhereNull('society_id');
                })
                ->orderBy('created_at', 'desc')->paginate(10);
        
        $societies = $user->societies;
        
        return view('posts.index', compact('posts', 'societies'));
    }

    public function user_post_create(Request $req){
        try{
            // dd($req->all());
            if($user = Auth::user()){
            $post = new Post();
            $post->user_id = $user->id;
            $post->society_id = $req->society_id;
            $post->content = $req->content;
            if ($req->hasFile('attachment')) {
                foreach ($req->file('attachment') as $file) {
                $imagePath = $file->store('post_images', 'public');
                $post->image = $imagePath;
                }
            }
            $post->status = "active";
            $post->save();
            return redirect()->route('dashboard');
        }
        }catch(\Exception $e){
            return "There is an error: " . $e->getMessage();
        }
    }

    public function toggleLike(Post $post)
{
    $user = auth()->user();
    $liked = $post->likes()->where('user_id', $user->id)->exists();

    if ($liked) {
        $post->likes()->where('user_id', $user->id)->delete();
    } else {
        $post->likes()->create(['user_id' => $user->id]);
    }

    return response()->json([
        'liked' => !$liked,
        'count' => $post->likes()->count(),
        'postUserId' => $post->user->id,
        'postUserSubscriptionId' => $post->user->subscription_token,
        'ReceptorUserId' => $post->user->id,
        'ReceptorUserName' => $post->user->name,
        'InitiatorName' => Auth::user()->name,
        'InitiatorId' => Auth::user()->id,
    ]);
}

public function post_comment(Request $request)
{
    $request->validate([
        'content' => 'required|string|max:500',
        'post_id' => 'required|exists:posts,id',
    ]);

    $comment = Comment::create([
        'user_id' => auth()->id(),
        'post_id' => $request->post_id,
        'content' => $request->content,
    ]);

    $post = Post::where('id', $request->post_id)->first();


    return response()->json([
        'content' => $comment->content,
        'user_name' => auth()->user()->name,
        'profile_picture' => auth()->user()->profile_picture,
        'postUserId' => $post->user->id,
        'postUserSubscriptionId' => $post->user->subscription_token,
        'ReceptorUserId' => $post->user->id,
        'ReceptorUserName' => $post->user->name,
        'InitiatorName' => Auth::user()->name,
        'InitiatorId' => Auth::user()->id,
    ]);
    
}


    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $societies = $user->societies;
        
        return view('posts.create', compact('societies'));
    }

    /**
     * Store a newly created post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'society_id' => 'nullable|exists:societies,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        
        // Check if user is a member of the society (if specified)
        if ($request->society_id) {
            $society = Society::query()->where('id', $request->society_id)->first();
            if (!$society || !$user->societies->contains($society->id)) {
                return redirect()->route('posts.create')
                    ->with('error', 'You can only post to societies you are a member of.');
            }
        }
        
        $post = new Post();
        $post->content = $request->content;
        $post->user_id = $user->id;
        $post->society_id = $request->society_id;
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post_images', 'public');
            $post->image = $imagePath;
        }
        
        $post->save();
        
        // Create notifications for society members if post is in a society
        if ($request->society_id) {
            $society = Society::query()->where('id', $request->society_id)->first();
            foreach ($society->users as $member) {
                if ($member->id != $user->id) { // Don't notify the creator
                    $notification = new Notification();
                    $notification->user_id = $member->id;
                    $notification->type = 'post';
                    $notification->data = json_encode([
                        'post_id' => $post->id,
                        'sender_id' => $user->id,
                        'sender_name' => $user->name,
                        'society_id' => $society->id,
                        'society_name' => $society->name,
                    ]);
                    $notification->save();
                }
            }
        }

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->load(['user', 'society', 'comments.user', 'likes']);
        $user = Auth::user();
        $hasLiked = $post->likes->contains('user_id', $user->id);
        
        return view('posts.show', compact('post', 'hasLiked'));
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // Check if the user is the post creator
        if (Auth::id() !== $post->user_id && !Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            return redirect()->route('posts.index')
                ->with('error', 'You do not have permission to edit this post.');
        }
        
        $user = Auth::user();
        $societies = $user->societies;
        
        return view('posts.edit', compact('post', 'societies'));
    }

    /**
     * Update the specified post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // Check if the user is the post creator
        if (Auth::id() !== $post->user_id && !Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to edit this post.');
        }
        
        $request->validate([
            'content' => 'required|string',
            'society_id' => 'nullable|exists:societies,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        
        // Check if user is a member of the society (if specified)
        if ($request->society_id) {
            $society = Society::query()->where('id', $request->society_id)->first();
            if (!$society || !$user->societies->contains($society->id)) {
                return redirect()->route('posts.edit', $post)
                    ->with('error', 'You can only post to societies you are a member of.');
            }
        }
        
        $post->content = $request->content;
        $post->society_id = $request->society_id;

            if ($request->hasFile('attachment')) {
                // Delete old image if exists
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                foreach ($request->file('attachment') as $file) {
                $imagePath = $file->store('post_images', 'public');
                $post->image = $imagePath;
                }
            }
            if(!$request->hasFile('attachment') && $post->image){
                $post->image = "";
            }
        
        $post->save();

        return redirect()->route('dashboard', $post)
            ->with('status', 'Post updated successfully!');
    }

    /**
     * Remove the specified post from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // Check if the user is the post creator or an admin
        if (Auth::id() !== $post->user_id && !Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to delete this post.');
        }
        // Delete image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }  
        // Delete associated comments and likes
        $post->comments()->delete();
        $post->likes()->delete();
        
        // Delete post
        $post->delete();
        return redirect()->route('dashboard')
            ->with('status', 'Post deleted successfully!');
    }
    
    /**
     * Like or unlike a post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function like(Post $post)
    {
        $user = Auth::user();
        $like = Like::query()
            ->where('user_id', $user->id)
            ->where('likeable_id', $post->id)
            ->where('likeable_type', Post::class)
            ->first();
        
        if ($like) {
            // Unlike the post
            $like->delete();
            $message = 'Post unliked.';
        } else {
            // Like the post
            $like = new Like();
            $like->user_id = $user->id;
            $like->likeable_id = $post->id;
            $like->likeable_type = Post::class;
            $like->save();
            $message = 'Post liked!';
            
            // Create notification for post owner (if it's not the same user)
            if ($post->user_id != $user->id) {
                $notification = new Notification();
                $notification->user_id = $post->user_id;
                $notification->type = 'like';
                $notification->data = json_encode([
                    'post_id' => $post->id,
                    'sender_id' => $user->id,
                    'sender_name' => $user->name,
                    'content_type' => 'post',
                ]);
                $notification->save();
            }
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'likes_count' => $post->likes()->count()
            ]);
        }
        
        return redirect()->back()->with('success', $message);
    }
}