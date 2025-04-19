<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Society;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,super_admin,sub_admin')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the announcements.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get societies the user is a member of
        $societyIds = $user->societies->pluck('id')->toArray();
        
        // Get announcements for these societies and general announcements (no society_id)
        $announcements = Announcement::query()
            ->where(function($query) use ($societyIds) {
                $query->whereIn('society_id', $societyIds)
                      ->orWhereNull('society_id');
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get societies that the user can make announcements for
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            // Admins can post to any society, or as a general announcement
            $societies = Society::all();
        } else {
            // Sub-admins can only post to societies they lead
            $societies = $user->ledSocieties;
        }
        
        return view('announcements.create', compact('societies'));
    }

    /**
     * Store a newly created announcement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'society_id' => 'nullable|exists:societies,id',
            'is_pinned' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $user = Auth::user();
        
        // Check permission for the specified society
        if ($request->society_id) {
            $society = Society::query()->where('id', $request->society_id)->first();
            
            // Sub-admins can only post announcements to societies they lead
            if ($user->isSubAdmin() && $society->leader_id != $user->id) {
                return redirect()->route('announcements.create')
                    ->with('error', 'You do not have permission to post announcements to this society.');
            }
        } else {
            // Only admins can post general announcements (without a society)
            if (!$user->isAdmin() && !$user->isSuperAdmin()) {
                return redirect()->route('announcements.create')
                    ->with('error', 'You do not have permission to post general announcements.');
            }
        }
        
        $announcement = new Announcement();
        $announcement->title = $request->title;
        $announcement->content = $request->content;
        $announcement->user_id = $user->id;
        $announcement->society_id = $request->society_id;
        $announcement->is_pinned = $request->has('is_pinned') ? $request->is_pinned : false;
        $announcement->expires_at = $request->expires_at;
        $announcement->save();
        
        // Create notifications for all users in the society, if applicable
        if ($request->society_id) {
            $society = Society::query()->where('id', $request->society_id)->first();
            foreach ($society->users as $member) {
                if ($member->id != $user->id) { // Don't notify the creator
                    $notification = new Notification();
                    $notification->user_id = $member->id;
                    $notification->type = 'announcement';
                    $notification->data = json_encode([
                        'announcement_id' => $announcement->id,
                        'title' => $announcement->title,
                        'sender_id' => $user->id,
                        'sender_name' => $user->name,
                        'society_id' => $society->id,
                        'society_name' => $society->name,
                    ]);
                    $notification->save();
                }
            }
        } else {
            // For general announcements, notify all users except the creator
            $users = User::query()->where('id', '!=', $user->id)->get();
            foreach ($users as $member) {
                $notification = new Notification();
                $notification->user_id = $member->id;
                $notification->type = 'announcement';
                $notification->data = json_encode([
                    'announcement_id' => $announcement->id,
                    'title' => $announcement->title,
                    'sender_id' => $user->id,
                    'sender_name' => $user->name,
                ]);
                $notification->save();
            }
        }

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Display the specified announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        $announcement->load(['user', 'society']);
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        $user = Auth::user();
        
        // Check permission to edit
        if (!$this->canModifyAnnouncement($announcement)) {
            return redirect()->route('announcements.index')
                ->with('error', 'You do not have permission to edit this announcement.');
        }
        
        // Get societies that the user can make announcements for
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            // Admins can post to any society, or as a general announcement
            $societies = Society::all();
        } else {
            // Sub-admins can only post to societies they lead
            $societies = $user->ledSocieties;
        }
        
        return view('announcements.edit', compact('announcement', 'societies'));
    }

    /**
     * Update the specified announcement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        if (!$this->canModifyAnnouncement($announcement)) {
            return redirect()->route('announcements.index')
                ->with('error', 'You do not have permission to edit this announcement.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'society_id' => 'nullable|exists:societies,id',
            'is_pinned' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $user = Auth::user();
        
        // Check permission for the specified society
        if ($request->society_id) {
            $society = Society::query()->where('id', $request->society_id)->first();
            
            // Sub-admins can only post announcements to societies they lead
            if ($user->isSubAdmin() && $society->leader_id != $user->id) {
                return redirect()->route('announcements.edit', $announcement)
                    ->with('error', 'You do not have permission to post announcements to this society.');
            }
        } else {
            // Only admins can post general announcements (without a society)
            if (!$user->isAdmin() && !$user->isSuperAdmin()) {
                return redirect()->route('announcements.edit', $announcement)
                    ->with('error', 'You do not have permission to post general announcements.');
            }
        }
        
        $announcement->title = $request->title;
        $announcement->content = $request->content;
        $announcement->society_id = $request->society_id;
        $announcement->is_pinned = $request->has('is_pinned') ? $request->is_pinned : false;
        $announcement->expires_at = $request->expires_at;
        $announcement->save();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        if (!$this->canModifyAnnouncement($announcement)) {
            return redirect()->route('announcements.index')
                ->with('error', 'You do not have permission to delete this announcement.');
        }
        
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }
    
    /**
     * Check if the currently authenticated user can modify the given announcement.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return bool
     */
    private function canModifyAnnouncement(Announcement $announcement)
    {
        $user = Auth::user();
        
        // Super admins can modify any announcement
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Admins can modify any announcement they created or any announcement for the societies they manage
        if ($user->isAdmin()) {
            return $announcement->user_id == $user->id || 
                   ($announcement->society_id && $user->ledSocieties->contains('id', $announcement->society_id));
        }
        
        // Sub-admins can only modify announcements they created for societies they lead
        if ($user->isSubAdmin()) {
            return $announcement->user_id == $user->id && 
                   $announcement->society_id && 
                   $user->ledSocieties->contains('id', $announcement->society_id);
        }
        
        return false;
    }
}