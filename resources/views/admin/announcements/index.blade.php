@extends('layouts.app')

@section('title', 'Manage Announcements')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Manage Announcements</h2>
        <p class="text-muted">Create, edit, and manage all announcements.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Create Announcement
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-0">All Announcements</h5>
            </div>
            <div class="col-md-4">
                <form action="{{ route('admin.announcements.index') }}" method="GET" class="admin-filter-form">
                    <select name="type" class="form-select form-select-sm" aria-label="Filter by type">
                        <option value="">All Types</option>
                        <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                        <option value="results" {{ request('type') == 'results' ? 'selected' : '' }}>Results</option>
                        <option value="holiday" {{ request('type') == 'holiday' ? 'selected' : '' }}>Holiday</option>
                        <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Event</option>
                        <option value="society" {{ request('type') == 'society' ? 'selected' : '' }}>Society</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Posted By</th>
                        <th>Society</th>
                        <th>Status</th>
                        <th>Date Range</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                    <tr>
                        <td>{{ $announcement->title }}</td>
                        <td>
                            <span class="badge 
                                @if($announcement->type == 'general') bg-primary
                                @elseif($announcement->type == 'results') bg-success
                                @elseif($announcement->type == 'holiday') bg-info
                                @elseif($announcement->type == 'event') bg-warning
                                @elseif($announcement->type == 'society') bg-secondary
                                @endif">
                                {{ ucfirst($announcement->type) }}
                            </span>
                        </td>
                        <td>{{ $announcement->user->name }}</td>
                        <td>{{ $announcement->society ? $announcement->society->name : 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $announcement->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($announcement->status) }}
                            </span>
                        </td>
                        <td>
                            @if($announcement->start_date && $announcement->end_date)
                                {{ $announcement->start_date->format('M d, Y') }} to {{ $announcement->end_date->format('M d, Y') }}
                            @elseif($announcement->start_date)
                                From {{ $announcement->start_date->format('M d, Y') }}
                            @elseif($announcement->end_date)
                                Until {{ $announcement->end_date->format('M d, Y') }}
                            @else
                                No date restrictions
                            @endif
                        </td>
                        <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="d-inline" data-confirm="Are you sure you want to delete this announcement?">
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
                        <td colspan="8" class="text-center py-4">No announcements found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $announcements->links() }}
        </div>
    </div>
</div>
@endsection
