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
                    <p>Add Society</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('society.add')}}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="mb-3">
                                        <img src="{{ asset('images/blank-profile.webp') }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="">
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
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="" required autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="bio" class="form-label">{{ __('Bio') }}</label>
                                    <textarea id="bio" class="form-control @error('bio') is-invalid @enderror" name="bio" rows="6" autocomplete="bio"></textarea>
                                    @error('bio')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="cover_photo" class="form-label">{{ __('Cover Photo') }}</label>
                                        <img src="{{ asset('images/blank-profile.webp') }}" class="mb-2 rounded" style="width: 100%; max-height: 200px; object-fit: cover;" alt="Cover Photo">
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
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </div>
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