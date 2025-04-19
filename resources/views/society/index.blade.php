@extends('layouts.app')

@section('title', 'Browse Societies')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Societies</h2>
        <p class="text-muted">Discover and join various campus societies and clubs.</p>
    </div>
    <div class="col-md-6">
        <form action="{{ route('societies.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search societies..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>

<div class="row">
    @forelse($societies as $society)
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card society-card h-100">
                @if($society->cover_image)
                    <img src="{{ Storage::url($society->cover_image) }}" class="card-img-top society-cover" alt="{{ $society->name }}">
                @else
                    <div class="society-cover bg-primary"></div>
                @endif
                
                @if($society->logo)
                    <img src="{{ Storage::url($society->logo) }}" class="society-logo" alt="{{ $society->name }}">
                @else
                    <div class="society-logo bg-white text-primary d-flex align-items-center justify-content-center">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                @endif
                
                <div class="card-body pt-4">
                    <h5 class="card-title">{{ $society->name }}</h5>
                    <p class="card-text society-description">{{ Str::limit($society->description, 100) }}</p>
                    <div class="text-center mt-3">
                        <a href="{{ route('societies.show', $society) }}" class="btn btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="bi bi-people text-muted display-4"></i>
                        <p class="mt-3 mb-0 text-muted">No societies found.</p>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $societies->links() }}
</div>

<div class="row mt-5">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h3>Want to start a new society?</h3>
                        <p class="mb-md-0">If you have an idea for a new society that doesn't exist yet, please contact the administration team to discuss the possibilities.</p>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <a href="mailto:admin@edconnect.com" class="btn btn-primary">
                            <i class="bi bi-envelope"></i> Contact Administration
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
