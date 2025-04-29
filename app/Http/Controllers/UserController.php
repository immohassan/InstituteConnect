<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function saveSubscriptionId(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|string',
        ]);

        $user = Auth::user(); // Make sure user is logged in

        if ($user) {
            $user->subscription_token = $request->subscription_id;
            $user->save();

            return response()->json(['message' => 'Subscription ID saved']);
        }

        return response()->json(['message' => 'Not authenticated'], 401);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $userId = $user->id;

        // Get the user model directly from database to ensure we have access to all methods
        $userModel = User::find($userId);

        if (!$userModel) {
            return back()->with('error', 'User not found.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'bio' => ['nullable', 'string', 'max:500'],
            'department' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'integer'],
            'semester' => ['nullable', 'integer'],
        ]);

        $userModel->name = $request->name;
        $userModel->email = $request->email;
        $userModel->bio = $request->bio;
        $userModel->department = $request->department;
        $userModel->year = $request->year;
        $userModel->semester = $request->semester;

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $userModel->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            $request->validate([
                'profile_picture' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            $fileName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('images/profile'), $fileName);
            $userModel->profile_picture = $fileName;
        }

        $userModel->save();

        return redirect()->route('profile.show', ['id' => $user->id])->with('status', 'Profile updated successfully.');
    }

    public function search(Request $request)
    {
        $users = User::where('name', 'like', '%' . $request->name . '%')->get();
        $html = '';

        foreach ($users as $user) {
            $profilePicture = $user->profile_picture
                ? asset('images/profile/' . $user->profile_picture)
                : asset('images/blank-profile.webp');

            $html .= '
            <div class="suggestion-block mt-2 d-flex align-items-center justify-content-between p-3 rounded shadow-sm"
                style="background-color: #1a1a1a; cursor: pointer;"
                data-url="' . route('profile.show', ['id' => $user->id]) . '">
                <div class="d-flex align-items-center">
                    <img src="' . $profilePicture . '" class="rounded-circle me-3" width="48" height="48">
                    <div>
                        <strong class="text-white me-1">' . e($user->name) . '</strong><i class="devicon-devicon-plain" title="Developer of the App"></i><br>
                        <small class="user-department">' . e($user->department ?? 'No Department Yet') . '</small>
                    </div>
                </div>
                <button class="btn btn-outline-light btn-sm px-4">Follow</button>
            </div>
        ';
        }

        return response($html);
    }
    public function follow(User $user)
    {
        Auth()->user()->following()->attach($user->id);
        $user->increment('followers');

        return response()->json([
            'success' => true,
            'ReceptorUserId' => $user->id,
            'ReceptorUserName' => $user->name,
            'InitiatorName' => Auth::user()->name,
            'InitiatorId' => Auth::user()->id,
            'postUserSubscriptionId' => $user->subscription_token,
            'follow' => true]);
    }

    public function unfollow(User $user)
    {
        auth()->user()->following()->detach($user->id);
        $user->decrement('followers');
        return response()->json([
            'success' => true,
            'postUserId' => $user->id,
            'postUserSubscriptionId' => $user->subscription_token,
            'follow' => false]);
    }
    public function staticSuggestions()
    {
        $users = User::latest()->take(10)->get(); // or however you load static suggestions

        $html = '';

        foreach ($users as $user) {
            $profilePicture = $user->profile_picture
                ? asset('images/profile/' . $user->profile_picture)
                : asset('images/blank-profile.webp');

            $html .= '
            <div class="suggestion-block mt-2 d-flex align-items-center justify-content-between p-3 rounded shadow-sm"
                style="background-color: #1a1a1a; cursor: pointer;"
                data-url="' . route('profile.view', ['id' => $user->id]) . '">
                <div class="d-flex align-items-center">
                    <img src="' . $profilePicture . '" class="rounded-circle me-3" width="48" height="48">
                    <div>
                        <strong class="text-white me-1">' . e($user->name) . '</strong><i class="devicon-devicon-plain" title="Developer of the App"></i><br>
                        <small class="user-department">' . e($user->department ?? 'No Department Yet') . '</small>
                    </div>
                </div>
                <button class="btn btn-outline-light btn-sm px-4">Follow</button>
            </div>
        ';
        }

        return response($html);
    }
}
