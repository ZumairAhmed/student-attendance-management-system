@extends('layouts.app')
@section('title', 'Edit Subject')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-edit me-2 text-primary"></i>Edit Subject</div>
            <div class="card-body p-4">
                <form action="{{ route('admin.subjects.update', $subject) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Subject Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $subject->name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Subject Code</label>
                            <input type="text" name="code" class="form-control"
                                value="{{ old('code', $subject->code) }}" required>
                            @error('code')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Batch</label>
                            <select name="batch_id" class="form-select" required>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ $subject->batch_id == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                @for($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}" {{ $subject->semester == $i ? 'selected' : '' }}>
                                        Semester {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Assign Lecturer</label>
                            <select name="user_id" class="form-select">
                                <option value="">Not Assigned</option>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}" {{ $subject->user_id == $lecturer->id ? 'selected' : '' }}>
                                        {{ $lecturer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Update Subject
                        </button>
                        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection