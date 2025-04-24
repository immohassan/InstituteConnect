<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the form for editing the authenticated user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    public function index($id){
        $user = User::findOrFail($id);

        $posts = Post::with(['user', 'comments', 'likes'])
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

        return view('profile.show', [
            'user' => $user,
            'posts' => $posts
        ]);
    }
}