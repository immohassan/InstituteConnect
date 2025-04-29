@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/edit-society.css') }}">
@endpush
@section('content')
<div class="container">
    <h2>Edit Society</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header mb-3 mt-3">
                    <p>Edit Society</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('society.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="mb-3">
                                    @if($user->society_picture)
                                        <img src="{{ asset('images/society/' . $user->society_picture) }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="{{ $user->name }}'s society">
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px;">
                                            <span class="text-white fs-1">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    {{--  <div class="mb-3">
                                        <label for="society_picture" class="form-label">{{ __('Society Picture') }}</label>
                                        <input type="file" class="form-control @error('society_picture') is-invalid @enderror" id="society_picture" name="society_picture">
                                        @error('society_picture')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>  --}}
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
    <form action="{{ route('society.edit', $society->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="name">Name</label>
        <input type="text" name="name" value="{{ $society->name }}" required>

        <label for="description">Description</label>
        <textarea name="description" required>{{ $society->description }}</textarea>

        <button type="submit">Update Society</button>
    </form>
</div>
@endsection


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