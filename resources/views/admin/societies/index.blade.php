@extends('layouts.app')

@section('title', 'Manage Societies')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Manage Societies</h2>
        <p class="text-muted">Create, edit, and manage campus societies.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
        <a href="{{ route('admin.societies.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create Society
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Societies</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>President</th>
                        <th>Members</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($societies as $society)
                    <tr>
                        <td>
                            @if($society->logo)
                                <img src="{{ Storage::url($society->logo) }}" alt="{{ $society->name }}" width="40" height="40" class="rounded-circle">
                            @else
                                <div class="avatar avatar-sm bg-primary text-white d-flex align-items-center justify-content-center">
                                    {{ strtoupper(substr($society->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $society->name }}</td>
                        <td>{{ Str::limit($society->description, 50) }}</td>
                        <td>
                            @if($society->president())
                                {{ $society->president()->name }}
                            @else
                                <span class="text-muted">None assigned</span>
                            @endif
                        </td>
                        <td>{{ $society->users->count() }}</td>
                        <td>
                            <span class="badge {{ $society->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($society->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('societies.show', $society) }}" class="btn btn-sm btn-outline-info me-1">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('admin.societies.edit', $society) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.societies.destroy', $society) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to delete this society? This will remove all related data.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No societies found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $societies->links() }}
        </div>
    </div>
</div>
@endsection
