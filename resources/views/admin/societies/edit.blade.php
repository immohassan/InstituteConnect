@extends('layouts.app')

@section('title', 'Edit Society')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Edit Society</h2>
        <p class="text-muted">Update society details and management.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('admin.societies.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Societies
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Society Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.societies.update', $society) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Society Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $society->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $society->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $society->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $society->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $society->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="logo" class="form-label">Society Logo</label>
                            @if($society->logo)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($society->logo) }}" alt="{{ $society->name }}" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                            <div class="form-text">Recommended size: 200x200 pixels. Max 2MB. Leave empty to keep current logo.</div>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="cover_image" class="form-label">Cover Image</label>
                            @if($society->cover_image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($society->cover_image) }}" alt="{{ $society->name }}" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*">
                            <div class="form-text">Recommended size: 1200x400 pixels. Max 2MB. Leave empty to keep current image.</div>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5>Society Leadership</h5>
                    
                    <div class="mb-3">
                        <label for="president_id" class="form-label">President <span class="text-danger">*</span></label>
                        <select class="form-select @error('president_id') is-invalid @enderror" id="president_id" name="president_id" required>
                            <option value="">Select President</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ old('president_id', $president ? $president->id : '') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">The president will be automatically assigned the sub-admin role if needed.</div>
                        @error('president_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Convenors (Optional)</label>
                        <div class="card">
                            <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                                @foreach($users as $user)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="convenor_ids[]" value="{{ $user->id }}" id="convenor{{ $user->id }}"
                                            {{ (is_array(old('convenor_ids')) && in_array($user->id, old('convenor_ids'))) || 
                                                ($convenors && $convenors->contains('id', $user->id)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="convenor{{ $user->id }}">
                                            {{ $user->name }} ({{ $user->email }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-text">Convenors will be automatically assigned the sub-admin role if needed.</div>
                        @error('convenor_ids')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Society
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Society Preview</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($society->logo)
                        <img src="{{ Storage::url($society->logo) }}" alt="{{ $society->name }}" class="img-thumbnail mb-3" style="max-height: 150px;">
                    @else
                        <div class="avatar avatar-xl bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3">
                            {{ strtoupper(substr($society->name, 0, 1)) }}
                        </div>
                    @endif
                    <h5>{{ $society->name }}</h5>
                    <p class="text-muted">{{ $society->email }}</p>
                </div>
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Status:</span>
                        <span class="badge {{ $society->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($society->status) }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Total Members:</span>
                        <span class="badge bg-primary rounded-pill">{{ $society->users->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Created:</span>
                        <span>{{ $society->created_at->format('M d, Y') }}</span>
                    </li>
                </ul>
                
                <div class="mt-3">
                    <a href="{{ route('societies.show', $society) }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-eye"></i> View Public Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
