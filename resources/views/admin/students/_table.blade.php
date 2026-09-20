<table class="table mb-0">
    <thead>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Index No</th>
            <th>Batch</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tableStudents as $student)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#3b82f6,#60a5fa);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:12px;font-weight:700;flex-shrink:0;">
                        {{ strtoupper(substr($student->full_name, 0, 1)) }}
                    </div>
                    <strong>{{ $student->full_name }}</strong>
                </div>
            </td>
            <td><span class="badge bg-light text-dark px-2 py-1">{{ $student->index_no }}</span></td>
            <td><span class="badge badge-info px-2 py-1 rounded-pill">{{ $student->batch->name }}</span></td>
            <td>{{ $student->email ?? '—' }}</td>
            <td>{{ $student->phone ?? '—' }}</td>
            <td>
                @if($student->status === 'active')
                    <span class="badge badge-present px-2 py-1 rounded-pill">Active</span>
                @else
                    <span class="badge badge-absent px-2 py-1 rounded-pill">Inactive</span>
                @endif
            </td>
            <td>
                <div class="d-flex gap-1">
                    <a href="{{ route('admin.students.show', $student) }}"
                        class="btn btn-sm btn-outline-info" title="View Profile">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.students.edit', $student) }}"
                        class="btn btn-sm btn-outline-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
                        onsubmit="return confirm('Delete {{ $student->full_name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-muted py-4">No students found.</td></tr>
        @endforelse
    </tbody>
</table>