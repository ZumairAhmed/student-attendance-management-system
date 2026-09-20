@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, ' . auth()->user()->name . ' 👋')
@section('content')

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="fas fa-user-graduate" style="color:#3b82f6;"></i>
            </div>
            <div class="stat-number">{{ $totalStudents }}</div>
            <div class="stat-label">Total Students</div>
            <div class="stat-change up"><i class="fas fa-arrow-up me-1"></i>Both Batches</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="fas fa-chalkboard-teacher" style="color:#22c55e;"></i>
            </div>
            <div class="stat-number">{{ $totalLecturers }}</div>
            <div class="stat-label">Active Lecturers</div>
            <div class="stat-change up"><i class="fas fa-circle me-1" style="font-size:8px;"></i>All Active</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf4ff;">
                <i class="fas fa-book-open" style="color:#a855f7;"></i>
            </div>
            <div class="stat-number">{{ $totalSubjects }}</div>
            <div class="stat-label">Total Subjects</div>
            <div class="stat-change" style="color:#a855f7;"><i class="fas fa-layer-group me-1"></i>{{ $totalBatches }} Batches</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed;">
                <i class="fas fa-exclamation-triangle" style="color:#f97316;"></i>
            </div>
            <div class="stat-number">{{ $lowAttendanceCount }}</div>
            <div class="stat-label">Low Attendance Alerts</div>
            <div class="stat-change down">
                @if($lowAttendanceCount > 0)
                    <i class="fas fa-arrow-up me-1"></i>Needs Attention
                @else
                    <i class="fas fa-check me-1" style="color:#16a34a;"></i><span style="color:#16a34a;">All Good!</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <div class="chart-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i>Students per Batch & Subject</h6>
            </div>
            <canvas id="subjectChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="chart-card">
            <h6><i class="fas fa-chart-pie me-2 text-primary"></i>Attendance Overview</h6>
            <canvas id="attendanceChart" height="200"></canvas>
            <div class="d-flex justify-content-center gap-3 mt-3">
                <div class="text-center">
                    <div style="font-size:20px;font-weight:800;color:#22c55e;">{{ $totalPresent ?? 0 }}</div>
                    <div style="font-size:11px;color:#94a3b8;">Present</div>
                </div>
                <div class="text-center">
                    <div style="font-size:20px;font-weight:800;color:#ef4444;">{{ $totalAbsent ?? 0 }}</div>
                    <div style="font-size:11px;color:#94a3b8;">Absent</div>
                </div>
                <div class="text-center">
                    <div style="font-size:20px;font-weight:800;color:#f59e0b;">{{ $totalLate ?? 0 }}</div>
                    <div style="font-size:11px;color:#94a3b8;">Late</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABLES ROW -->
<div class="row g-3 mb-4">
    @foreach($batches as $batch)
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-users me-2 text-primary"></i>{{ $batch->name }}</span>
                <span class="badge badge-info px-2 py-1 rounded-pill">{{ $batch->students->count() }} Students</span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Name</th><th>Index No</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($batch->students->take(5) as $student)
                        <tr>
                            <td><strong>{{ $student->full_name }}</strong></td>
                            <td><span class="badge bg-light text-dark">{{ $student->index_no }}</span></td>
                            <td><span class="badge badge-present px-2 py-1 rounded-pill">Active</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No students yet</td></tr>
                        @endforelse
                        @if($batch->students->count() > 5)
                        <tr>
                            <td colspan="3" class="text-center py-2">
                                <a href="{{ route('admin.students.index') }}" style="font-size:13px;color:#3b82f6;">
                                    View all {{ $batch->students->count() }} students →
                                </a>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- RECENT SESSIONS + LOW ATTENDANCE -->
<div class="row g-3">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-clock me-2 text-primary"></i>Recent Sessions</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Subject</th><th>Batch</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($recentSessions as $session)
                        <tr>
                            <td><strong>{{ $session->subject->name }}</strong></td>
                            <td>{{ $session->subject->batch->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No sessions yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Low Attendance</span>
                <a href="{{ route('admin.alerts') }}" class="btn btn-sm btn-outline-danger" style="font-size:12px;">View All</a>
            </div>
            <div class="card-body p-0">
                @if($lowAttendanceCount === 0)
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                    No low attendance alerts!
                </div>
                @else
                <div class="p-3">
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>{{ $lowAttendanceCount }}</strong> student(s) are below 80% threshold.
                        <a href="{{ route('admin.alerts') }}" class="alert-link ms-2">View details →</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
// Bar Chart - Students per Subject
const subjectLabels = @json($subjects->pluck('name'));
const subjectData = @json($subjects->map(fn($s) => \App\Models\Student::where('batch_id', $s->batch_id)->where('status','active')->count()));

new Chart(document.getElementById('subjectChart'), {
    type: 'bar',
    data: {
        labels: subjectLabels,
        datasets: [{
            label: 'Students',
            data: subjectData,
            backgroundColor: 'rgba(59,130,246,0.7)',
            borderColor: '#3b82f6',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});

// Pie Chart - Attendance
new Chart(document.getElementById('attendanceChart'), {
    type: 'doughnut',
    data: {
        labels: ['Present', 'Absent', 'Late'],
        datasets: [{
            data: [{{ $totalPresent ?? 0 }}, {{ $totalAbsent ?? 0 }}, {{ $totalLate ?? 0 }}],
            backgroundColor: ['#22c55e', '#ef4444', '#f59e0b'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: { legend: { display: false } }
    }
});
</script>
@endsection