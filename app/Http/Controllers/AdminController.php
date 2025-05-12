<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\Society;
use App\Models\Announcement;
use App\Models\Result;
use App\Models\Attendance;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function dashboard()
    {
        // Get counts for various statistics
        $userCount = User::count();
        $postCount = Post::count();
        $societyCount = Society::count();
        $announcementCount = Announcement::count();
        
        // Get recent posts
        $recentPosts = Post::with('user')
            ->latest()
            ->take(5)
            ->get();
            
        // Get recent announcements
        $recentAnnouncements = Announcement::with('user', 'society')
            ->latest()
            ->take(5)
            ->get();
            
        // Get recent users
        $recentUsers = User::latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'userCount', 
            'postCount', 
            'societyCount', 
            'announcementCount', 
            'recentPosts', 
            'recentAnnouncements', 
            'recentUsers'
        ));
    }

    /**
     * Display a listing of users.
     */
    public function users()
    {
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function updateUser(Request $request, User $user)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'department' => 'nullable|string|max:100',
            'student_id' => 'nullable|string|max:50',
            'year' => 'nullable|integer|min:1|max:6',
            'semester' => 'nullable|integer|min:1|max:12',
        ]);
        
        // Update user information
        $user->name = $request->name;
        $user->email = $request->email;
        $user->department = $request->department;
        $user->student_id = $request->student_id;
        $user->year = $request->year;
        $user->semester = $request->semester;
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        // Sync roles
        $user->syncRoles([$request->role]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroyUser(User $user)
    {
        // Check if user is not super admin (prevent deletion)
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Super Admin cannot be deleted.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }


    public function admin_portal_show(){
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $users = User::all();
        return view('admin.portal', [
            'users' => $users
        ]);
    }

    public function user_delete( Request $request){
        $user = User::findOrFail($request->id);
        if(Auth::user()->role == "admin" || Auth::user()->role == "super-admin" || Auth::user()->role == "dev"){
        $user->delete();
        return back()->with('success', 'User deleted successfully');
        }else{
            return back()->with('fail', 'You are not authorized to do that');
        }
    }

    public function user_update(Request $request){
        $user = User::findOrFail($request->id);
        $user->role = strtolower($request->role);
        $user->save();

        return back()->with('success', 'User updated successfully');
    }

    public function user_add(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@ibitpu.edu.pk')) {
                        $fail('Only @ibitpu.edu.pk email addresses are allowed.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()     // At least one uppercase and one lowercase letter
                    ->letters()       // Must contain letters
                    ->numbers()       // Must contain numbers
                    ->symbols(),      // Must contain special characters
            ],
        ]);
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = strtolower($request->role);
        $user->save();
        return back()->with("success", "User added succesfully");
    }
}
