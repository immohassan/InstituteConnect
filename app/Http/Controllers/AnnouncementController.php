<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Society;
use App\Models\Notification;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function admin_post_create(Request $req){
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
}