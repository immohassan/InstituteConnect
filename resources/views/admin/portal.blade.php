@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css" />
@endpush
@php
    if(Auth::user()->role == 'user'){
        Auth::logout();
        return redirect('/home');
    }
@endphp
@section('content')
<div class="main-heading">Admin Access Portal</div>
<div class="container main-section" id="post-container">
    <table id="myTable" class="display">
        <thead>
            <tr>
                <th>User Name</th>
                <th style="width: 30%">Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                    <i class="bi bi-pencil-square" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" style="cursor: pointer;"></i>
                    <i class="bi bi-trash2-fill" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}" style="cursor: pointer;"></i>
                </td>
            </tr>
            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1"
                aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-sm rounded-3 p-3"
                        style="max-width: 500px; margin: auto;">
                        <div class="modal-body text-center">
                            <h5 class="mb-3">Are you sure you want to delete this user?</h5>
                            <p class="text-muted small mb-4">This action cannot be undone.</p>
                            <form action="{{ route('user.delete', $user->id) }}" method="POST">
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

            <!-- Edit User Modal -->
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name{{ $user->id }}" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name{{ $user->id }}" name="name" value="{{ $user->name }}" required>
                    </div>
                    <!-- Role Dropdown -->
                    <div class="mb-3">
                        <label for="role{{ $user->id }}" class="form-label">Role</label>
                        <select class="form-select" id="role{{ $user->id }}" name="role" required>
                            @foreach (['User', 'Dev', 'Admin', 'Sub-admin', 'Super-Admin'] as $roleOption)
                                <option value="{{ $roleOption }}" {{ ucfirst($user->role) === $roleOption ? 'selected' : '' }}>{{ $roleOption }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@push('scripts')
<script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
<script>
    $(document).ready( function () {
    $('#myTable').DataTable({
        responsive: true,
        paging: true,
        searching: true,
        ordering: true
    });
} );
</script>

@endpush