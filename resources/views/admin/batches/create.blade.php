@extends('layouts.app')
@section('title', 'Add Batch')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2 text-primary"></i>Add New Batch</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.batches.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Batch Name</label>
                        <input type="text" name="name" class="form-control"
                            placeholder="e.g. HNDIT Year 1" value="{{ old('name') }}" required>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <input type="text" name="year" class="form-control"
                            placeholder="e.g. 2024" value="{{ old('year') }}" required>
                        @error('year')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Save Batch
                        </button>
                        <a href="{{ route('admin.batches.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection