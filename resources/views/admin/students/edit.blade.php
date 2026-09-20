@extends('layouts.app')
@section('title', 'Edit Student')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-edit me-2 text-primary"></i>Edit Student</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.students.update', $student) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control"
                                value="{{ old('full_name', $student->full_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Index Number</label>
                            <input type="text" name="index_no" class="form-control"
                                value="{{ old('index_no', $student->index_no) }}" required>
                            @error('index_no')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Batch</label>
                            <select name="batch_id" class="form-select" required>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ $student->batch_id == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $student->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $student->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ $student->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $student->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Update Student
                        </button>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection