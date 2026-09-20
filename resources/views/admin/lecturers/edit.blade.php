@extends('layouts.app')
@section('title', 'Edit Lecturer')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-edit me-2 text-primary"></i>Edit Lecturer</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.lecturers.update', $lecturer) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $lecturer->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $lecturer->email) }}" required>
                        @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control"
                            value="{{ old('phone', $lecturer->phone) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="text-muted">(Leave blank to keep current)</span></label>
                        <input type="password" name="password" class="form-control" placeholder="New password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $lecturer->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $lecturer->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Update Lecturer
                        </button>
                        <a href="{{ route('admin.lecturers.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection