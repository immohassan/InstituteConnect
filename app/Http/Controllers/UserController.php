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
    
    /**
     * Update the authenticated user's profile.
     */
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
        
        return back()->with('status', 'Profile updated successfully.');
    }
}