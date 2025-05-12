<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        $posts = Post::with(['user', 'comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('home.home', [
            'user' => $user,
            'posts' => $posts
        ]);
    }
}
