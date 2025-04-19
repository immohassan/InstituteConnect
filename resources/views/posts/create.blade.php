@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Create Post</h2>
        <p class="text-muted">Share your thoughts, ideas or updates with the community.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Feed
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">New Post</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">What's on your mind?</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="post-content" name="content" rows="5" placeholder="Share your thoughts..." required>{{ old('content') }}</textarea>
                        <div class="d-flex justify-content-end mt-1">
                            <small class="text-muted" id="char-counter">2000</small>
                        </div>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @if(count($societies) > 0)
                    <div class="mb-3">
                        <label for="society_id" class="form-label">Post to</label>
                        <select class="form-select @error('society_id') is-invalid @enderror" id="society_id" name="society_id">
                            <option value="">Your personal timeline</option>
                            @foreach($societies as $society)
                                <option value="{{ $society->id }}" {{ old('society_id') == $society->id ? 'selected' : '' }}>
                                    {{ $society->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">If you select a society, your post will be visible to all society members.</div>
                        @error('society_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Add an image (optional)</label>
                        <input type="file" class="form-control image-upload @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" data-preview="#image-preview">
                        <div class="form-text">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF.</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <img id="image-preview" src="#" alt="Preview" class="img-fluid rounded" style="max-height: 300px; display: none;">
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Posting Guidelines</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        Be respectful and considerate to others
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        Share valuable and relevant content
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        Use appropriate language and tone
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-x-circle-fill text-danger me-2"></i>
                        Avoid posting confidential information
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-x-circle-fill text-danger me-2"></i>
                        Don't spam or post inappropriate content
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-x-circle-fill text-danger me-2"></i>
                        Don't post offensive or harmful material
                    </li>
                </ul>
                
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Your post will be visible to all users or specific society members if selected.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
