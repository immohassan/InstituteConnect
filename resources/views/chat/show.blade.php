@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->name)

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Chat with {{ $otherUser->name }}</h2>
            <a href="{{ route('chats.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Messages
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 d-none d-md-block">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Conversations</h5>
            </div>
            <div class="card-body p-0">
                @php
                    $acceptedChats = App\Models\Chat::with(['sender', 'receiver', 'latestMessage'])
                        ->where(function($query) {
                            $query->where('sender_id', auth()->id())
                                ->orWhere('receiver_id', auth()->id());
                        })
                        ->where('status', 'accepted')
                        ->latest()
                        ->get();
                @endphp
                
                @if($acceptedChats->count() > 0)
                    <ul class="chat-list">
                        @foreach($acceptedChats as $chatItem)
                            @php
                                $chatOtherUser = $chatItem->sender_id == auth()->id() ? $chatItem->receiver : $chatItem->sender;
                                $latestMessage = $chatItem->latestMessage;
                                $unreadCount = App\Models\ChatMessage::where('chat_id', $chatItem->id)
                                    ->where('user_id', '!=', auth()->id())
                                    ->whereNull('read_at')
                                    ->count();
                            @endphp
                            <li class="chat-list-item {{ $chatItem->id == $chat->id ? 'active' : '' }}">
                                <a href="{{ route('chats.show', $chatItem) }}" class="d-flex align-items-center text-decoration-none text-dark">
                                    <div class="chat-avatar">
                                        @if($chatOtherUser->profile_picture)
                                            <img src="{{ Storage::url($chatOtherUser->profile_picture) }}" alt="{{ $chatOtherUser->name }}" class="avatar avatar-md">
                                        @else
                                            <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center">
                                                {{ strtoupper(substr($chatOtherUser->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="chat-info">
                                        <h6 class="chat-name">{{ $chatOtherUser->name }}</h6>
                                        <p class="chat-preview mb-0">
                                            @if($latestMessage)
                                                {{ Str::limit($latestMessage->message, 30) }}
                                            @else
                                                Start a conversation
                                            @endif
                                        </p>
                                    </div>
                                    <div class="chat-meta">
                                        @if($latestMessage)
                                            <span class="chat-time">{{ $latestMessage->created_at->format('h:i A') }}</span>
                                        @endif
                                        
                                        @if($unreadCount > 0)
                                            <div class="chat-badge">{{ $unreadCount }}</div>
                                        @endif
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center text-muted py-3">No conversations yet.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="chat-container" data-chat-id="{{ $chat->id }}">
                <div class="chat-header">
                    <div class="d-flex align-items-center">
                        @if($otherUser->profile_picture)
                            <img src="{{ Storage::url($otherUser->profile_picture) }}" alt="{{ $otherUser->name }}" class="avatar avatar-md">
                        @else
                            <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center">
                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="chat-user">
                            <h5 class="mb-0">{{ $otherUser->name }}</h5>
                            <small class="text-muted">
                                @if($otherUser->department)
                                    {{ $otherUser->department }}
                                @else
                                    Member
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="chat-messages">
                    @forelse($chat->messages as $message)
                        <div class="chat-message {{ $message->user_id == auth()->id() ? 'outgoing' : 'incoming' }}">
                            <div class="message-content">{{ $message->message }}</div>
                            <div class="message-time">{{ $message->created_at->format('h:i A') }}</div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-chat-dots text-muted display-4"></i>
                            <p class="mt-3 mb-0 text-muted">No messages yet.</p>
                            <p class="text-muted">Start your conversation with {{ $otherUser->name }}!</p>
                        </div>
                    @endforelse
                </div>
                
                <form id="chat-form" class="chat-form" action="{{ route('chats.messages.store', $chat) }}" method="POST" data-chat-id="{{ $chat->id }}">
                    @csrf
                    <input type="text" name="message" class="form-control" placeholder="Type your message..." required autofocus>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
