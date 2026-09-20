@extends('layouts.app')
@section('title', 'Manage Lecturers')
@section('subtitle', 'All lecturer accounts')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fas fa-file-excel me-2"></i>Import Excel/CSV
        </button>
        
        <a href="{{ route('admin.lecturers.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-2"></i>Add Lecturer
        </a>
        <a href="{{ route('admin.lecturers.pdf') }}" target="_blank" class="btn btn-outline-danger btn-sm">
    <i class="fas fa-file-pdf me-2"></i>Download PDF
</a>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-chalkboard-teacher me-2 text-primary"></i>All Lecturers</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subjects</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lecturers as $lecturer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:linear-gradient(135deg,#1a3a8f,#3b82f6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:12px;font-weight:700;flex-shrink:0;">
                                {{ strtoupper(substr($lecturer->name, 0, 1)) }}
                            </div>
                            <strong>{{ $lecturer->name }}</strong>
                        </div>
                    </td>
                    <td>{{ $lecturer->email }}</td>
                    <td>{{ $lecturer->phone ?? '—' }}</td>
                    <td>
                        <span class="badge badge-info px-2 py-1 rounded-pill">
                            {{ $lecturer->subjects->count() ?? 0 }} subjects
                        </span>
                    </td>
                    <td>
                        @if($lecturer->status === 'active')
                            <span class="badge badge-present px-2 py-1 rounded-pill">Active</span>
                        @else
                            <span class="badge badge-absent px-2 py-1 rounded-pill">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.lecturers.edit', $lecturer) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.lecturers.destroy', $lecturer) }}"
                                method="POST" onsubmit="return confirm('Delete {{ $lecturer->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No lecturers found.</td></tr>
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
                    <i class="fas fa-file-excel me-2 text-success"></i>Import Lecturers
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info mb-3">
                    <strong>Required columns:</strong> name, email, phone, password<br>
                    <small>Default password is <strong>lecturer123</strong> if not specified</small>
                </div>
                <form action="{{ route('admin.lecturers.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Excel or CSV File</label>
                        <input type="file" name="csv_file" class="form-control"
                            accept=".xlsx,.xls,.csv" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-upload me-2"></i>Import Lecturers
                        </button>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection