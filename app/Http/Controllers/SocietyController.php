<?php

namespace App\Http\Controllers;

use App\Models\Society;
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
        $this->middleware('role:admin,super_admin')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the societies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $societies = Society::with('users')->get();
        return view('society.index', compact('societies'));
    }

    /**
     * Show the form for creating a new society.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('society.create', compact('users'));
        // $user = Auth::user();


        // return view('society.create', [
        //     'user' => $user,
        // ]);
    }

    /**
     * Store a newly created society in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:societies',
            'description' => 'required|string',
            'leader_id' => 'required|exists:users,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $society = new Society();
        $society->name = $request->name;
        $society->description = $request->description;
        $society->leader_id = $request->leader_id;

        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('society_covers', 'public');
            $society->cover_image = $imagePath;
        }

        $society->save();

        // Add leader to society members
        $society->users()->attach($request->leader_id);

        // Update user role to sub_admin if they aren't already an admin or super_admin
        $leader = User::query()->where('id', $request->leader_id)->first();
        if ($leader && !in_array($leader->role, ['admin', 'super_admin'])) {
            $leader->role = 'sub_admin';
            $leader->save();
        }

        return redirect()->route('societies.index')
            ->with('success', 'Society created successfully!');
    }

    /**
     * Display the specified society.
     *
     * @param  \App\Models\Society  $society
     * @return \Illuminate\Http\Response
     */
    public function show(Society $society)
    {
        $society->load(['users', 'announcements', 'leader']);
        $isMember = $society->users->contains(Auth::id());

        return view('society.show', compact('society', 'isMember'));
    }

    /**
     * Show the form for editing the specified society.
     *
     * @param  \App\Models\Society  $society
     * @return \Illuminate\Http\Response
     */
    public function edit(Society $society)
    {
        $users = User::all();
        // return view('society.edit', compact('society', 'users'));
        return view('society.edit', compact('society'));

        return redirect()->route('societies.index')->with('success', 'Society updated successfully!');
    }

    /**
     * Update the specified society in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Society  $society
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Society $society)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('societies')->ignore($society->id),
            ],
            'description' => 'required|string',
            'leader_id' => 'required|exists:users,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $oldLeaderId = $society->leader_id;

        $society->name = $request->name;
        $society->description = $request->description;
        $society->leader_id = $request->leader_id;

        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($society->cover_image) {
                Storage::disk('public')->delete($society->cover_image);
            }

            $imagePath = $request->file('cover_image')->store('society_covers', 'public');
            $society->cover_image = $imagePath;
        }

        $society->save();

        // Ensure leader is a member
        if (!$society->users->contains($request->leader_id)) {
            $society->users()->attach($request->leader_id);
        }

        // Update leader's role if changed
        if ($oldLeaderId != $request->leader_id) {
            $newLeader = User::query()->where('id', $request->leader_id)->first();
            if ($newLeader && !in_array($newLeader->role, ['admin', 'super_admin'])) {
                $newLeader->role = 'sub_admin';
                $newLeader->save();
            }
        }

        return redirect()->route('societies.index')
            ->with('success', 'Society updated successfully!');
    }

    /**
     * Remove the specified society from storage.
     *
     * @param  \App\Models\Society  $society
     * @return \Illuminate\Http\Response
     */
    public function destroy(Society $society)
    {
        // Delete cover image if exists
        if ($society->cover_image) {
            Storage::disk('public')->delete($society->cover_image);
        }

        // Detach all users
        $society->users()->detach();

        // Delete society
        $society->delete();

        return redirect()->route('societies.index')
            ->with('success', 'Society deleted successfully!');
    }

    /**
     * Join a society.
     *
     * @param  \App\Models\Society  $society
     * @return \Illuminate\Http\Response
     */
    public function join(Society $society)
    {
        $user = Auth::user();

        if (!$society->users->contains($user->id)) {
            $society->users()->attach($user->id);
            return redirect()->route('societies.show', $society)
                ->with('success', 'You have joined this society!');
        }

        return redirect()->route('societies.show', $society)
            ->with('info', 'You are already a member of this society.');
    }

    /**
     * Leave a society.
     *
     * @param  \App\Models\Society  $society
     * @return \Illuminate\Http\Response
     */
    public function leave(Society $society)
    {
        $user = Auth::user();

        // Cannot leave if you're the leader
        if ($society->leader_id == $user->id) {
            return redirect()->route('societies.show', $society)
                ->with('error', 'As a leader, you cannot leave this society. Please assign another leader first.');
        }

        if ($society->users->contains($user->id)) {
            $society->users()->detach($user->id);
            return redirect()->route('societies.show', $society)
                ->with('success', 'You have left this society.');
        }

        return redirect()->route('societies.show', $society)
            ->with('info', 'You are not a member of this society.');
    }
}
