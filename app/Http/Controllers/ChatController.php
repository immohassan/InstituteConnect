<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the user's chats.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get accepted chats
        $acceptedChats = Chat::with(['sender', 'receiver', 'latestMessage'])
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->latest()
            ->get();
            
        // Get pending chat requests (received by the user)
        $pendingRequests = Chat::with('sender')
            ->where('receiver_id', $user->id)
            ->where('status', 'requested')
            ->latest()
            ->get();
            
        // Get sent requests that are still pending
        $sentRequests = Chat::with('receiver')
            ->where('sender_id', $user->id)
            ->where('status', 'requested')
            ->latest()
            ->get();
            
        return view('chat.index', compact('acceptedChats', 'pendingRequests', 'sentRequests'));
    }

    /**
     * Display the specified chat.
     */
    public function show(Chat $chat)
    {
        $user = Auth::user();
        
        // Check if user is part of this chat
        if ($chat->sender_id !== $user->id && $chat->receiver_id !== $user->id) {
            return redirect()->route('chats.index')
                ->with('error', 'You do not have permission to view this chat.');
        }
        
        // Check if chat is accepted
        if ($chat->status !== 'accepted') {
            return redirect()->route('chats.index')
                ->with('error', 'This chat request has not been accepted yet.');
        }
        
        // Load relationships
        $chat->load(['sender', 'receiver', 'messages.user']);
        
        // Mark unread messages as read
        ChatMessage::where('chat_id', $chat->id)
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        // Get the other user in the chat
        $otherUser = $chat->getOtherUser($user->id);
        
        return view('chat.show', compact('chat', 'otherUser'));
    }

    /**
     * Store a new chat message.
     */
    public function storeMessage(Request $request, Chat $chat)
    {
        $user = Auth::user();
        
        // Check if user is part of this chat
        if ($chat->sender_id !== $user->id && $chat->receiver_id !== $user->id) {
            return redirect()->route('chats.index')
                ->with('error', 'You do not have permission to message in this chat.');
        }
        
        // Check if chat is accepted
        if ($chat->status !== 'accepted') {
            return redirect()->route('chats.index')
                ->with('error', 'This chat request has not been accepted yet.');
        }
        
        // Validate request
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        
        // Create message
        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $request->message,
        ]);
        
        // Get the other user
        $otherUser = $chat->getOtherUser($user->id);
        
        // Create notification for the other user
        Notification::create([
            'user_id' => $otherUser->id,
            'from_user_id' => $user->id,
            'type' => 'chat_message',
            'content' => "{$user->name} sent you a message",
            'link' => route('chats.show', $chat->id),
        ]);
        
        // If using AJAX, return the message
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('user'),
            ]);
        }
        
        return redirect()->route('chats.show', $chat->id)
            ->with('success', 'Message sent successfully!');
    }

    /**
     * Send a chat request to a user.
     */
    public function sendRequest(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Check if user can send chat requests
        if (!$currentUser->can('send_chat_requests')) {
            return redirect()->route('profile.show', $user->id)
                ->with('error', 'You do not have permission to send chat requests.');
        }
        
        // Check if user is not trying to chat with themselves
        if ($currentUser->id === $user->id) {
            return redirect()->route('profile.show', $user->id)
                ->with('error', 'You cannot send a chat request to yourself.');
        }
        
        // Check if a chat already exists between these users
        $existingChat = Chat::where(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $currentUser->id)
                    ->where('receiver_id', $user->id);
            })
            ->orWhere(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $currentUser->id);
            })
            ->first();
            
        if ($existingChat) {
            if ($existingChat->status === 'accepted') {
                return redirect()->route('chats.show', $existingChat->id)
                    ->with('info', 'You already have a chat with this user.');
            } elseif ($existingChat->status === 'requested') {
                if ($existingChat->sender_id === $currentUser->id) {
                    return redirect()->route('chats.index')
                        ->with('info', 'You have already sent a chat request to this user.');
                } else {
                    return redirect()->route('chats.index')
                        ->with('info', 'This user has already sent you a chat request.');
                }
            } elseif ($existingChat->status === 'declined') {
                // If previously declined, update to requested
                $existingChat->status = 'requested';
                $existingChat->sender_id = $currentUser->id;
                $existingChat->receiver_id = $user->id;
                $existingChat->save();
                
                // Create notification for the receiver
                Notification::create([
                    'user_id' => $user->id,
                    'from_user_id' => $currentUser->id,
                    'type' => 'chat_request',
                    'content' => "{$currentUser->name} has sent you a chat request",
                    'link' => route('chats.index'),
                ]);
                
                return redirect()->route('chats.index')
                    ->with('success', 'Chat request sent successfully!');
            }
        }
        
        // Create new chat request
        $chat = Chat::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $user->id,
            'status' => 'requested',
        ]);
        
        // Create notification for the receiver
        Notification::create([
            'user_id' => $user->id,
            'from_user_id' => $currentUser->id,
            'type' => 'chat_request',
            'content' => "{$currentUser->name} has sent you a chat request",
            'link' => route('chats.index'),
        ]);
        
        return redirect()->route('chats.index')
            ->with('success', 'Chat request sent successfully!');
    }

    /**
     * Accept a chat request.
     */
    public function acceptRequest(Chat $chat)
    {
        $user = Auth::user();
        
        // Check if user is the receiver of this chat request
        if ($chat->receiver_id !== $user->id) {
            return redirect()->route('chats.index')
                ->with('error', 'You cannot accept this chat request.');
        }
        
        // Check if chat is still in requested status
        if ($chat->status !== 'requested') {
            return redirect()->route('chats.index')
                ->with('error', 'This chat request cannot be accepted.');
        }
        
        // Update chat status to accepted
        $chat->status = 'accepted';
        $chat->save();
        
        // Create notification for the sender
        Notification::create([
            'user_id' => $chat->sender_id,
            'from_user_id' => $user->id,
            'type' => 'chat_request_accepted',
            'content' => "{$user->name} has accepted your chat request",
            'link' => route('chats.show', $chat->id),
        ]);
        
        return redirect()->route('chats.show', $chat->id)
            ->with('success', 'Chat request accepted!');
    }

    /**
     * Decline a chat request.
     */
    public function declineRequest(Chat $chat)
    {
        $user = Auth::user();
        
        // Check if user is the receiver of this chat request
        if ($chat->receiver_id !== $user->id) {
            return redirect()->route('chats.index')
                ->with('error', 'You cannot decline this chat request.');
        }
        
        // Check if chat is still in requested status
        if ($chat->status !== 'requested') {
            return redirect()->route('chats.index')
                ->with('error', 'This chat request cannot be declined.');
        }
        
        // Update chat status to declined
        $chat->status = 'declined';
        $chat->save();
        
        // Create notification for the sender
        Notification::create([
            'user_id' => $chat->sender_id,
            'from_user_id' => $user->id,
            'type' => 'chat_request_declined',
            'content' => "{$user->name} has declined your chat request",
            'link' => route('chats.index'),
        ]);
        
        return redirect()->route('chats.index')
            ->with('success', 'Chat request declined.');
    }
}
