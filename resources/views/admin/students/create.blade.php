@extends('layouts.app')
@section('title', 'Add Student')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2 text-primary"></i>Add New Student</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.students.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control"
                                placeholder="Student's full name" value="{{ old('full_name') }}" required>
                            @error('full_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Index Number</label>
                            <input type="text" name="index_no" class="form-control"
                                placeholder="e.g. KAN/IT/2324/F/001" value="{{ old('index_no') }}" required>
                            @error('index_no')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Batch</label>
                            <select name="batch_id" class="form-select" required>
                                <option value="">Select Batch</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('batch_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-muted">(Optional)</span></label>
                            <input type="email" name="email" class="form-control"
                                placeholder="student12@gmail.com" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone <span class="text-muted">(Optional)</span></label>
                            <input type="text" name="phone" class="form-control"
                                placeholder="07XXXXXXXX" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Save Student
                        </button>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection