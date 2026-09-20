@extends('layouts.app')
@section('title', 'Manage Subjects')
@section('subtitle', 'All subjects and lecturer assignments')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fas fa-file-excel me-2"></i>Import Excel/CSV
        </button>
        
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-2"></i>Add Subject
        </a>
        <a href="{{ route('admin.subjects.pdf') }}" target="_blank" class="btn btn-outline-danger btn-sm">
    <i class="fas fa-file-pdf me-2"></i>Download PDF
</a>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-book-open me-2 text-primary"></i>All Subjects</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject Name</th>
                    <th>Code</th>
                    <th>Batch</th>
                    <th>Semester</th>
                    <th>Lecturer</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $subject->name }}</strong></td>
                    <td><span class="badge badge-info px-2 py-1">{{ $subject->code }}</span></td>
                    <td>{{ $subject->batch->name }}</td>
                    <td>Semester {{ $subject->semester }}</td>
                    <td>{{ $subject->lecturer->name ?? '—' }}</td>
                    <td>
                        @if($subject->is_locked)
                            <span class="badge badge-absent px-2 py-1 rounded-pill">Locked</span>
                        @else
                            <span class="badge badge-present px-2 py-1 rounded-pill">Active</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            @if($subject->is_locked)
                            <form action="{{ route('admin.subjects.unlock', $subject) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-outline-success" title="Unlock">
                                    <i class="fas fa-unlock"></i>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.subjects.lock', $subject) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-outline-warning" title="Lock">
                                    <i class="fas fa-lock"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('admin.subjects.edit', $subject) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.subjects.destroy', $subject) }}"
                                method="POST" onsubmit="return confirm('Delete this subject?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No subjects found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- IMPORT MODAL --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:18px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-excel me-2 text-success"></i>Import Subjects
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info mb-3">
                    <strong>Required columns:</strong> name, code, batch_id, semester, lecturer_email<br>
                    <small>lecturer_email is optional — leave blank if not assigned yet</small>
                </div>
                <form action="{{ route('admin.subjects.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Excel or CSV File</label>
                        <input type="file" name="csv_file" class="form-control"
                            accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-upload me-2"></i>Import Subjects
                        </button>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection