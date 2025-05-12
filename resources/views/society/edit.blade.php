@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/edit-profile.css') }}">
<link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
@endpush
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header mb-3 mt-3">
                    <p>Edit Society</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('society.update', ['id' => $society->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="mb-3">
                                    @if($society->logo)
                                        <img src="{{ asset('images/' . $society->logo) }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="{{ $society->name }}'s profile">
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px;">
                                            <span class="text-white fs-1">{{ strtoupper(substr($society->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div class="mb-3">
                                        <label for="profile_picture" class="form-label">{{ __('Society Logo') }}</label>
                                        <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" id="profile_picture" name="profile_picture">
                                        @error('profile_picture')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $society->name) }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $society->email) }}" required autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="bio" class="form-label">{{ __('Bio') }}</label>
                                    <textarea id="bio" class="form-control @error('bio') is-invalid @enderror" name="bio" rows="6" autocomplete="bio">{{ old('bio', $society->description) }}</textarea>
                                    @error('bio')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="cover_photo" class="form-label">{{ __('Cover Photo') }}</label>
                                    @if($society->cover_image)
                                        <img src="{{ asset('images/' . $society->cover_image) }}" class="mb-2 rounded" style="width: 100%; max-height: 200px; object-fit: cover;" alt="Cover Photo">
                                    @endif
                                    <input type="file" class="form-control @error('cover_photo') is-invalid @enderror" id="cover_photo" name="cover_photo">
                                    @error('cover_photo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePasswordFields() {
        let passwordFields = document.getElementById('passwordFields');
        let changePassword = document.getElementById('changePassword');
        
        if (changePassword.checked) {
            passwordFields.style.display = 'block';
        } else {
            passwordFields.style.display = 'none';
            document.getElementById('password').value = '';
            document.getElementById('password-confirm').value = '';
        }
    }
</script>
@endpush
@endsection