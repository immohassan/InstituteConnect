@extends('layouts.app')
@section('title', 'Resources | Campus Connect')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
@endpush
@section('content')
    <div class="main-heading">Resources</div>
    <div class="container main-section" id="post-container">
        <p class="text-center text-white" style="font-size:20px;">Semester {{ $id }}</p>
        <div class="table-responsive-xl mt-4">
            <table class="table table-bordered table-dark">
                <thead>
                    <tr>
                        <th scope="col" style="width: 8%">#</th>
                        <th scope="col" style="width: 40%">Subject</th>
                        <th scope="col">File Name</th>
                        <th scope="col" style="width: 10%">Ac</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resources as $key => $resource)
                    @php
                        $semester_id = $id;
                        $subject_name = $resource->subject_name;
                    @endphp
                        <tr class="text-white-50">
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $resource->subject_name }}</td>
                            <td><a style="text-decoration: none;" href="{{ asset('docs/' . $resource->file_name) }}" download>
                                {{ explode('_', $resource->file_name)[1] ?? $resource->file_name }}
                            </a></td>
                            <td><i class="bi bi-trash-fill btn-danger" style="cursor: pointer;" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $resource->id }}"></i></td>
                        </tr>
                        <div class="modal fade" id="deleteModal{{ $resource->id }}" tabindex="-1"
                            aria-labelledby="deleteModalLabel{{ $resource->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-sm rounded-3 p-3"
                                    style="max-width: 500px; margin: auto;">
                                    <div class="modal-body text-center">
                                        <h5 class="mb-3">Are you sure you want to delete this rsource?</h5>
                                        <p class="text-muted small mb-4">This action cannot be undone.</p>
                                        <form action="{{ route('resources.delete', $resource->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-sm btn-light rounded-pill px-4"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit"
                                                    class="btn btn-sm btn-danger rounded-pill px-4">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        <button class="create-post-btn btn btn-primary rounded-circle shadow rounded-pill"
            data-bs-placement="left" data-bs-toggle="modal" data-bs-target="#addResourceModal"
            title="Add Resource">
            <i class="bi bi-plus-lg"></i>
    </button>
    </div>
                <!-- Add Resource Modal -->
                <div class="modal fade" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('resources.add', ['semester_id' => $semester_id]) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addResourceModalLabel">Add Resource</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="subject_name" class="form-label">Subject Name</label>
                                    <input type="text" class="form-control" name="subject_name" value="{{ $subject_name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="file_name" class="form-label">File</label>
                                    <input type="file" class="form-control" name="file_name" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Resource</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @push('scripts')
    @endpush
@endsection
