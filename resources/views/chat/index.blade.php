@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2>Messages</h2>
        <p class="text-muted">View and manage your chats with other users.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Chat Requests</h5>
            </div>
            <div class="card-body p-0">
                @if($pendingRequests->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($pendingRequests as $request)
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    @if($request->sender->profile_picture)
                                        <img src="{{ Storage::url($request->sender->profile_picture) }}" alt="{{ $request->sender->name }}" class="avatar avatar-md me-3">
                                    @else
                                        <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                            {{ strtoupper(substr($request->sender->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $request->sender->name }}</h6>
                                        <small class="text-muted">Wants to chat with you</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <form action="{{ route('chats.accept', $request) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success me-1">Accept</button>
                                    </form>
                                    <form action="{{ route('chats.decline', $request) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Decline</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted py-3">No pending chat requests.</p>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Sent Requests</h5>
            </div>
            <div class="card-body p-0">
                @if($sentRequests->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($sentRequests as $request)
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    @if($request->receiver->profile_picture)
                                        <img src="{{ Storage::url($request->receiver->profile_picture) }}" alt="{{ $request->receiver->name }}" class="avatar avatar-md me-3">
                                    @else
                                        <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                            {{ strtoupper(substr($request->receiver->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $request->receiver->name }}</h6>
                                        <small class="text-muted">Request pending</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted py-3">No sent requests waiting for approval.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Conversations</h5>
            </div>
            <div class="card-body p-0">
                @if($acceptedChats->count() > 0)
                    <ul class="chat-list">
                        @foreach($acceptedChats as $chat)
                            @php
                                $otherUser = $chat->sender_id == auth()->id() ? $chat->receiver : $chat->sender;
                                $latestMessage = $chat->latestMessage;
                                $unreadCount = App\Models\ChatMessage::where('chat_id', $chat->id)
                                    ->where('user_id', '!=', auth()->id())
                                    ->whereNull('read_at')
                                    ->count();
                            @endphp
                            <li class="chat-list-item {{ request()->is('chats/'.$chat->id) ? 'active' : '' }}">
                                <a href="{{ route('chats.show', $chat) }}" class="d-flex align-items-center text-decoration-none text-dark">
                                    <div class="chat-avatar">
                                        @if($otherUser->profile_picture)
                                            <img src="{{ Storage::url($otherUser->profile_picture) }}" alt="{{ $otherUser->name }}" class="avatar avatar-md">
                                        @else
                                            <div class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center">
                                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="chat-info">
                                        <h6 class="chat-name">{{ $otherUser->name }}</h6>
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
                    <div class="text-center py-5">
                        <i class="bi bi-chat-dots text-muted display-4"></i>
                        <p class="mt-3 mb-0 text-muted">No conversations yet.</p>
                        <p class="text-muted">Start chatting by sending a message request.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-chat-square-text text-primary display-1"></i>
                    <h3 class="mt-3">Welcome to Messages</h3>
                    <p class="text-muted">Select a conversation from the sidebar or start a new chat by visiting someone's profile.</p>
                    <p class="text-muted">You can also accept or decline pending chat requests from other users.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
