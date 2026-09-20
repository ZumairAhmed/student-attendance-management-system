@extends('layouts.app')
@section('title', 'Manage Students')
@section('subtitle', 'All enrolled students across both batches')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fas fa-file-excel me-2"></i>Import Excel/CSV
        </button>
        
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-2"></i>Add Student
        </a>
        <a href="{{ route('admin.students.pdf') }}" target="_blank" class="btn btn-outline-danger btn-sm">
    <i class="fas fa-file-pdf me-2"></i>Download PDF
</a>
    </div>
</div>

{{-- BATCH TABS --}}
<ul class="nav nav-pills mb-4 gap-2" id="batchTabs">
    <li class="nav-item">
        <button class="nav-link active" onclick="showBatch('all')" id="tab-all"
            style="border-radius:10px;font-size:13px;font-weight:600;">
            All Students ({{ $students->count() }})
        </button>
    </li>
    @foreach($batches as $batch)
    <li class="nav-item">
        <button class="nav-link" onclick="showBatch({{ $batch->id }})" id="tab-{{ $batch->id }}"
            style="border-radius:10px;font-size:13px;font-weight:600;">
            {{ $batch->name }} ({{ $students->where('batch_id', $batch->id)->count() }})
        </button>
    </li>
    @endforeach
</ul>

{{-- ALL --}}
<div id="batch-all">
    <div class="card">
        <div class="card-header"><i class="fas fa-users me-2 text-primary"></i>All Students</div>
        <div class="card-body p-0">
            @include('admin.students._table', ['tableStudents' => $students])
        </div>
    </div>
</div>

{{-- PER BATCH --}}
@foreach($batches as $batch)
<div id="batch-{{ $batch->id }}" style="display:none;">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-layer-group me-2 text-primary"></i>{{ $batch->name }} — {{ $batch->year }}</span>
            <span class="badge badge-info px-2 py-1 rounded-pill">
                {{ $students->where('batch_id', $batch->id)->count() }} Students
            </span>
        </div>
        <div class="card-body p-0">
            @include('admin.students._table', ['tableStudents' => $students->where('batch_id', $batch->id)])
        </div>
    </div>
</div>
@endforeach

{{-- IMPORT MODAL --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:18px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-excel me-2 text-success"></i>Import Students
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <div class="alert alert-info mb-0">
                            <strong><i class="fas fa-info-circle me-2"></i>How to Import:</strong>
                            <ol class="mb-0 mt-2" style="font-size:13px;">
                                <li>Download the template file below</li>
                                <li>Fill in student details in Excel or save as CSV</li>
                                <li>Use correct batch_id (Year 1 = <strong>{{ $batches->first()->id ?? '?' }}</strong>, Year 2 = <strong>{{ $batches->last()->id ?? '?' }}</strong>)</li>
                                <li>Upload the file and click Import</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div style="background:#f8fafc;border-radius:12px;padding:16px;">
                            <div style="font-size:13px;font-weight:700;color:#374151;margin-bottom:10px;">
                                Required Columns:
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['full_name', 'index_no', 'batch_id', 'email (optional)', 'phone (optional)'] as $col)
                                <span class="badge badge-info px-3 py-2" style="font-size:12px;">{{ $col }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Excel or CSV File</label>
                        <input type="file" name="csv_file" class="form-control"
                            accept=".xlsx,.xls,.csv" required>
                        <div style="font-size:12px;color:#94a3b8;margin-top:4px;">
                            Supported: .xlsx, .xls, .csv
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-upload me-2"></i>Import Students
                        </button>
                       
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
function showBatch(id) {
    document.querySelectorAll('[id^="batch-"]').forEach(el => el.style.display = 'none');
    document.querySelectorAll('[id^="tab-"]').forEach(el => el.classList.remove('active'));
    document.getElementById('batch-' + id).style.display = 'block';
    document.getElementById('tab-' + id).classList.add('active');
}
</script>
@endsection