@extends('layouts.app')
@section('title', 'Manage Batches')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h6 class="text-muted mb-1">Manage all HNDIT batches</h6>
    </div>
    <a href="{{ route('admin.batches.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Batch
    </a>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-layer-group me-2 text-primary"></i>All Batches</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Batch Name</th>
                    <th>Year</th>
                    <th>Students</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batches as $batch)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $batch->name }}</strong></td>
                    <td>{{ $batch->year }}</td>
                    <td>{{ $batch->students_count }}</td>
                    <td>
                        @if($batch->status === 'active')
                            <span class="badge badge-present px-2 py-1 rounded-pill">Active</span>
                        @else
                            <span class="badge badge-absent px-2 py-1 rounded-pill">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.batches.edit', $batch) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.batches.destroy', $batch) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Delete this batch?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No batches found. Add one!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection