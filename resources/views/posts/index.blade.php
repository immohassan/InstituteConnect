@extends('layouts.app')

@section('title', 'Posts Feed')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Posts Feed</h2>
                @can('create_posts')
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Create Post
                    </a>
                @endcan
            </div>

            @if (request('society_id'))
                @php
                    $society = App\Models\Society::find(request('society_id'));
                @endphp
                @if ($society)
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            @if ($society->logo)
                                <img src="{{ Storage::url($society->logo) }}" alt="{{ $society->name }}"
                                    class="avatar avatar-md me-3">
                            @else
                                <div
                                    class="avatar avatar-md bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                    {{ strtoupper(substr($society->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-0">Viewing posts from {{ $society->name }}</h5>
                                <a href="{{ route('posts.index') }}">Clear filter</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @forelse($posts as $post)
                {{-- @foreach ($posts as $post)
                    <p>{{ $post->title }}</p>
                @endforeach --}}

                {{ $posts->links() }}
                @include('components.post-card', ['post' => $post])
            @empty
                <div class="card">
                    <div class="card-body">
                        <div class="text-center py-5">
                            <i class="bi bi-newspaper text-muted display-4"></i>
                            <p class="mt-3 mb-0 text-muted">No posts to display yet.</p>
                            @can('create_posts')
                                <a href="{{ route('posts.create') }}" class="btn btn-primary mt-3">Create your first post</a>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Your Societies</h5>
                </div>
                <div class="card-body">
                    @if (auth()->user()->societies->count() > 0)
                        <div class="list-group">
                            @foreach (auth()->user()->societies as $society)
                                <a href="{{ route('posts.index', ['society_id' => $society->id]) }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center">
                                    @if ($society->logo)
                                        <img src="{{ Storage::url($society->logo) }}" alt="{{ $society->name }}"
                                            class="avatar avatar-sm me-3">
                                    @else
                                        <div
                                            class="avatar avatar-sm bg-primary text-white d-flex align-items-center justify-content-center me-3">
                                            {{ strtoupper(substr($society->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $society->name }}</h6>
                                        <small class="text-muted">{{ ucfirst($society->pivot->role) }}</small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted py-3">You are not a member of any society yet.</p>
                        <a href="{{ route('societies.index') }}" class="btn btn-outline-primary w-100">Browse Societies</a>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Announcements</h5>
                </div>
                <div class="card-body">
                    @php
                        $announcements = App\Models\Announcement::with('user', 'society')
                            ->where('status', 'active')
                            ->where(function ($query) {
                                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
                            })
                            ->latest()
                            ->take(3)
                            ->get();
                    @endphp

                    @if ($announcements->count() > 0)
                        @foreach ($announcements as $announcement)
                            @include('components.announcement-card', [
                                'announcement' => $announcement,
                                'compact' => true,
                            ])
                        @endforeach

                        <div class="text-center mt-3">
                            <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-primary">View All
                                Announcements</a>
                        </div>
                    @else
                        <p class="text-center text-muted py-3">No announcements to display.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
