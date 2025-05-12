<?php

namespace App\Http\Controllers;

use App\Models\Society;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SocietyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,super-admin,dev,sub-admin')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        $societies = Society::with('users')->get();
        return view('society.index', compact('societies'));
    }

    public function show(Request $req)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        $posts = Post::all();
        $society = Society::findOrFail($req->id);
        return view('society.show', [
            'society' => $society,
            'user' => $user,
            'posts' => $posts
        ]);
    }


    public function edit(Request $req)
    {
        $society = Society::findOrFail($req->id);
        return view('society.edit', [
            'society' => $society
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $society = Society::findOrFail($request->id);
        $society->name = $request->name;
        $society->description = $request->bio;
        $society->email = $request->email;
        if($request->profile_picture){
            $file = $request->file('profile_picture');
            $fileName = time() . '_' . $file->getClientOriginalName(); // optional: Str::random(10) for unique names
            $file->move(public_path('images'), $fileName); // Moves to public/images/
            $society->logo = $fileName; // Save just the name or 'images/'.$fileName if needed
        }
        if($request->cover_photo){
            $file = $request->file('cover_photo');
            $fileName = time() . '_' . $file->getClientOriginalName(); // optional: Str::random(10) for unique names
            $file->move(public_path('images'), $fileName); // Moves to public/images/
            $society->cover_image = $fileName; // Save just the name or 'images/'.$fileName if needed
        }

        $society->save();
        return redirect()->route('societies.show', ['id' => $society->id])
            ->with('success', 'Society updated successfully!');
    }

    public function delete(Request $req){
        $society = Society::findOrFail($req->id);
        $society->delete();
        return redirect()->route('societies')->with('success', 'Society deleted successfully.');
    }

    public function new(){
        return view('society.add');
    }

    public function add(Request $request){
        // dd($req->all());
        $society = new Society();
        $society->name = $request->name;
        $society->description = $request->bio;
        $society->email = $request->email;
        $society->status = "active";
        $society->followers = 0;
        if($request->profile_picture){
            $file = $request->file('profile_picture');
            $fileName = time() . '_' . $file->getClientOriginalName(); // optional: Str::random(10) for unique names
            $file->move(public_path('images'), $fileName); // Moves to public/images/
            $society->logo = $fileName; // Save just the name or 'images/'.$fileName if needed
        }
        if($request->cover_photo){
            $file = $request->file('cover_photo');
            $fileName = time() . '_' . $file->getClientOriginalName(); // optional: Str::random(10) for unique names
            $file->move(public_path('images'), $fileName); // Moves to public/images/
            $society->cover_image = $fileName; // Save just the name or 'images/'.$fileName if needed
        }

        $society->save();
        return redirect()->route('societies.show', ['id' => $society->id])
            ->with('success', 'Society updated successfully!');
    }

    public function follow($id)
{
    $user = auth()->user();
    $society = Society::findOrFail($id);

    // Attach user if not already following
    if (!$user->followedSocieties->contains($id)) {
        $user->followedSocieties()->attach($id);
        $society->increment('followers');
    }

    return response()->json(['followers' => $society->followers,'status' => 'followed' ]);
}

public function unfollow($id)
{
    $user = auth()->user();
    $society = Society::findOrFail($id);

    if ($user->followedSocieties->contains($id)) {
        $user->followedSocieties()->detach($id);
        $society->decrement('followers');
    }

    return response()->json(['followers' => $society->followers, 'status' => 'unfollowed']);
}

}