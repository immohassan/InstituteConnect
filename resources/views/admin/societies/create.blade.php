@extends('layouts.app')

@section('title', 'Create Society')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Create Society</h2>
        <p class="text-muted">Create a new campus society.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('admin.societies.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Societies
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Society Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.societies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Society Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="logo" class="form-label">Society Logo</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                            <div class="form-text">Recommended size: 200x200 pixels. Max 2MB.</div>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="cover_image" class="form-label">Cover Image</label>
                            <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*">
                            <div class="form-text">Recommended size: 1200x400 pixels. Max 2MB.</div>
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
                                <option value="{{ $user->id }}" {{ old('president_id') == $user->id ? 'selected' : '' }}>
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
                                            {{ (is_array(old('convenor_ids')) && in_array($user->id, old('convenor_ids'))) ? 'checked' : '' }}>
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
                        <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create Society
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
