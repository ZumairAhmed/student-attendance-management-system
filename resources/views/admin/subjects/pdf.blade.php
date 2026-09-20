<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Subjects List</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }

        .header {
            background: #0a1e5e; color: white;
            padding: 18px 24px; margin-bottom: 20px;
        }
        .header h1 { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        .header p { font-size: 11px; opacity: 0.8; }

        .meta {
            display: flex; gap: 20px;
            padding: 0 24px; margin-bottom: 16px;
        }
        .meta-box {
            background: #f1f5f9; border-radius: 6px;
            padding: 8px 14px; flex: 1;
        }
        .meta-box .label { font-size: 9px; color: #64748b; text-transform: uppercase; }
        .meta-box .value { font-size: 14px; font-weight: bold; color: #0f172a; margin-top: 2px; }

        .batch-title {
            background: #1a3a8f; color: white;
            padding: 8px 24px; font-size: 12px;
            font-weight: bold; margin-bottom: 0;
        }

        table {
            width: calc(100% - 48px);
            margin: 0 24px 20px;
            border-collapse: collapse;
        }
        th {
            background: #e2e8f0; padding: 9px 10px;
            text-align: left; font-size: 10px;
            font-weight: bold; color: #374151;
            text-transform: uppercase;
        }
        td {
            padding: 8px 10px; font-size: 11px;
            border-bottom: 1px solid #f1f5f9;
            color: #374151;
        }
        tr:nth-child(even) td { background: #f8fafc; }

        .badge {
            padding: 2px 8px; border-radius: 10px;
            font-size: 9px; font-weight: bold;
        }
        .badge-active  { background: #dcfce7; color: #16a34a; }
        .badge-locked  { background: #fee2e2; color: #dc2626; }
        .badge-code    { background: #dbeafe; color: #1d4ed8; }

        .footer {
            margin-top: 20px; padding: 10px 24px;
            font-size: 9px; color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            display: flex; justify-content: space-between;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Subject List — HNDIT Programme</h1>
    <p>Advanced Technological Institute — Dambawela Premises | Department of Information Technology</p>
</div>

<div class="meta">
    <div class="meta-box">
        <div class="label">Total Subjects</div>
        <div class="value">{{ $subjects->count() }}</div>
    </div>
    <div class="meta-box">
        <div class="label">Assigned</div>
        <div class="value">{{ $subjects->whereNotNull('user_id')->count() }}</div>
    </div>
    <div class="meta-box">
        <div class="label">Unassigned</div>
        <div class="value">{{ $subjects->whereNull('user_id')->count() }}</div>
    </div>
    <div class="meta-box">
        <div class="label">Generated On</div>
        <div class="value">{{ now()->format('d M Y') }}</div>
    </div>
</div>

@php $batches = $subjects->groupBy('batch_id'); @endphp

@foreach($batches as $batchId => $batchSubjects)
@php $batch = $batchSubjects->first()->batch; @endphp

<div class="batch-title">
    {{ $batch->name }} — {{ $batch->year }}
    ({{ $batchSubjects->count() }} Subjects)
</div>

<table>
    <thead>
        <tr>
            <th style="width:30px;">#</th>
            <th>Subject Name</th>
            <th>Code</th>
            <th>Semester</th>
            <th>Assigned Lecturer</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($batchSubjects as $subject)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $subject->name }}</strong></td>
            <td><span class="badge badge-code">{{ $subject->code }}</span></td>
            <td>Semester {{ $subject->semester }}</td>
            <td>{{ $subject->lecturer->name ?? '— Not Assigned —' }}</td>
            <td>
                @if($subject->is_locked)
                    <span class="badge badge-locked">Locked</span>
                @else
                    <span class="badge badge-active">Active</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endforeach

<div class="footer">
    <span>SAMS — Student Attendance Management System · SLIATE Dambawela</span>
    <span>Generated: {{ now()->format('d M Y, h:i A') }}</span>
</div>

</body>
</html>