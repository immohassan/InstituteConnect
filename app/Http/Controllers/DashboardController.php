<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Announcement;
use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Get recent posts from users the logged-in user follows
        // or posts from societies they are part of
        // For now, we'll just get the most recent posts
        $posts = Post::with(['user', 'comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        // Get announcements
        $announcements = Announcement::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get societies the user is part of
        $societies = Society::all(); // In a real app, filter by user membership
        
        return view('dashboard', [
            'user' => $user,
            'posts' => $posts,
            'announcements' => $announcements,
            'societies' => $societies,
        ]);
    }
}