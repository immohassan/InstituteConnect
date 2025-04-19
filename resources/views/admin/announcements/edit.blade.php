@extends('layouts.app')

@section('title', 'Edit Announcement')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Edit Announcement</h2>
        <p class="text-muted">Update announcement details.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Announcements
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Announcement Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $announcement->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="general" {{ old('type', $announcement->type) == 'general' ? 'selected' : '' }}>General</option>
                                <option value="results" {{ old('type', $announcement->type) == 'results' ? 'selected' : '' }}>Results</option>
                                <option value="holiday" {{ old('type', $announcement->type) == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                <option value="event" {{ old('type', $announcement->type) == 'event' ? 'selected' : '' }}>Event</option>
                                <option value="society" {{ old('type', $announcement->type) == 'society' ? 'selected' : '' }}>Society</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6" required>{{ old('content', $announcement->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="society_id" class="form-label">Society (Optional)</label>
                            <select class="form-select @error('society_id') is-invalid @enderror" id="society_id" name="society_id">
                                <option value="">None (Applies to All)</option>
                                @foreach($societies as $society)
                                    <option value="{{ $society->id }}" {{ old('society_id', $announcement->society_id) == $society->id ? 'selected' : '' }}>
                                        {{ $society->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">If selected, only members of this society will see the announcement.</div>
                            @error('society_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $announcement->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="archived" {{ old('status', $announcement->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date (Optional)</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" 
                                value="{{ old('start_date', $announcement->start_date ? $announcement->start_date->format('Y-m-d') : '') }}">
                            <div class="form-text">Leave empty for immediate start.</div>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date (Optional)</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" 
                                value="{{ old('end_date', $announcement->end_date ? $announcement->end_date->format('Y-m-d') : '') }}">
                            <div class="form-text">Leave empty for no expiration.</div>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
