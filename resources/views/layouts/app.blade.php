<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAMS | @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; overflow-x: hidden; }
        .main-content { overflow-y: auto; }

        .sidebar {
            width: 255px; min-height: 100vh;
            background: linear-gradient(180deg, #0a1e5e 0%, #1a3a8f 60%, #1e50a0 100%);
            position: fixed; top: 0; left: 0; z-index: 100;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }
        .sidebar-brand {
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand .brand-logo {
            width: 42px; height: 42px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 10px;
        }
        .sidebar-brand h4 { color: white; font-weight: 800; font-size: 17px; margin: 0; }
        .sidebar-brand p { color: rgba(255,255,255,0.5); font-size: 11px; margin: 3px 0 0; }

        .sidebar-nav { padding: 12px 0; flex: 1; overflow-y: auto; max-height: calc(100vh - 160px); }
        .nav-section {
            padding: 10px 20px 4px;
            font-size: 10px; font-weight: 700;
            color: rgba(255,255,255,0.35);
            letter-spacing: 1.5px; text-transform: uppercase;
        }
        .sidebar a {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 18px; margin: 2px 10px;
            color: rgba(255,255,255,0.7); text-decoration: none;
            font-size: 13.5px; font-weight: 500;
            transition: all 0.2s; border-radius: 10px;
        }
        .sidebar a:hover { background: rgba(255,255,255,0.1); color: white; }
        .sidebar a.active {
            background: rgba(255,255,255,0.15);
            color: white; font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .sidebar a.active i { color: #60a5fa; }
        .sidebar a i { width: 18px; font-size: 14px; }
        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
            position: sticky;
            bottom: 0;
            background: linear-gradient(180deg, #1a3a8f, #1e50a0);
            z-index: 10;
        }
        .user-info { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .user-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            color: white; font-size: 15px; font-weight: 700;
        }
        .user-name { color: white; font-size: 13px; font-weight: 600; }
        .user-role { color: rgba(255,255,255,0.45); font-size: 11px; }
        .btn-logout {
            width: 100%; background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.7); border-radius: 10px;
            padding: 8px; font-size: 13px; cursor: pointer; transition: all 0.2s;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.25); border-color: rgba(239,68,68,0.4); color: white; }

        .main-content { margin-left: 255px; min-height: 100vh; }

        .topbar {
            background: white; padding: 0 28px;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50; height: 64px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }
        .topbar-left h5 { font-weight: 700; color: #0f172a; margin: 0; font-size: 17px; }
        .topbar-left p { font-size: 12px; color: #94a3b8; margin: 0; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .notif-btn {
            position: relative; width: 40px; height: 40px;
            background: #f1f5f9; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; border: none; color: #64748b;
            font-size: 16px; transition: all 0.2s;
        }
        .notif-btn:hover { background: #e2e8f0; color: #1e293b; }
        .notif-badge {
            position: absolute; top: -4px; right: -4px;
            background: #ef4444; color: white;
            font-size: 10px; font-weight: 700;
            width: 18px; height: 18px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid white;
        }
        .notif-dropdown {
            position: absolute; top: 52px; right: 20px;
            width: 340px; background: white;
            border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            border: 1px solid #e2e8f0; z-index: 200; display: none;
        }
        .notif-dropdown.show { display: block; }
        .notif-header {
            padding: 16px 18px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 700; font-size: 14px; color: #0f172a;
        }
        .notif-item {
            padding: 12px 18px; border-bottom: 1px solid #f8fafc;
            display: flex; gap: 12px; align-items: flex-start;
        }
        .notif-item:hover { background: #f8fafc; }
        .notif-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; flex-shrink: 0;
        }
        .notif-text { font-size: 13px; color: #374151; line-height: 1.5; }
        .notif-time { font-size: 11px; color: #94a3b8; margin-top: 3px; }
        .notif-empty { padding: 28px; text-align: center; color: #94a3b8; font-size: 13px; }

        .date-badge {
            background: #f1f5f9; border-radius: 10px;
            padding: 8px 14px; font-size: 12px; color: #64748b;
            display: flex; align-items: center; gap: 6px;
        }

        .page-content { padding: 24px 28px; }

        .stat-card {
            background: white; border-radius: 18px;
            padding: 22px; border: 1px solid #e8edf5;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative; overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.08); }
        .stat-card::after {
            content: ''; position: absolute;
            top: -20px; right: -20px;
            width: 80px; height: 80px; border-radius: 50%;
            opacity: 0.06;
        }
        .stat-icon {
            width: 50px; height: 50px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; margin-bottom: 14px;
        }
        .stat-number { font-size: 30px; font-weight: 800; color: #0f172a; line-height: 1; }
        .stat-label { font-size: 13px; color: #94a3b8; margin-top: 5px; font-weight: 500; }
        .stat-change { font-size: 12px; margin-top: 8px; font-weight: 600; }
        .stat-change.up { color: #16a34a; }
        .stat-change.down { color: #dc2626; }

        .card { border: 1px solid #e8edf5; border-radius: 18px; box-shadow: none; background: white; }
        .card-header {
            background: white; border-bottom: 1px solid #f1f5f9;
            border-radius: 18px 18px 0 0 !important;
            padding: 16px 20px; font-weight: 700;
            color: #0f172a; font-size: 14px;
        }
        .table th {
            font-size: 11px; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.6px;
            border-bottom: 2px solid #f1f5f9; padding: 12px 16px; background: #fafbfc;
        }
        .table td { padding: 12px 16px; vertical-align: middle; font-size: 13.5px; color: #374151; border-bottom: 1px solid #f8fafc; }
        .table tbody tr:hover td { background: #fafbff; }
        .table tbody tr:last-child td { border-bottom: none; }

        .badge-present { background: #dcfce7; color: #16a34a; }
        .badge-absent  { background: #fee2e2; color: #dc2626; }
        .badge-late    { background: #fef9c3; color: #ca8a04; }
        .badge-info    { background: #dbeafe; color: #1d4ed8; }

        .btn-primary {
            background: linear-gradient(135deg, #1a3a8f, #3b82f6);
            border: none; border-radius: 10px;
            font-weight: 600; font-size: 13.5px;
        }
        .btn-primary:hover { background: linear-gradient(135deg, #1e3a8a, #2563eb); }

        .alert { border: none; border-radius: 12px; font-size: 13.5px; }
        .alert-success { background: #f0fdf4; color: #16a34a; }
        .alert-danger  { background: #fef2f2; color: #dc2626; }
        .alert-warning { background: #fffbeb; color: #d97706; }
        .alert-info    { background: #eff6ff; color: #1d4ed8; }

        .form-control, .form-select {
            border: 2px solid #e2e8f0; border-radius: 11px;
            font-size: 13.5px; padding: 10px 14px; background: #fafbfc;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,0.1); background: white;
        }
        .form-label { font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px; }

        .percentage-bar { height: 8px; border-radius: 4px; background: #e2e8f0; overflow: hidden; }
        .percentage-fill { height: 100%; border-radius: 4px; transition: width 0.5s ease; }

        .chart-card { background: white; border-radius: 18px; padding: 22px; border: 1px solid #e8edf5; }
        .chart-card h6 { font-weight: 700; color: #0f172a; margin-bottom: 16px; font-size: 14px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo"><i class="fas fa-graduation-cap" style="color:white;font-size:18px;"></i></div>
        <h4>SAMS</h4>
        <p>SLIATE Dambawela · HNDIT</p>
    </div>

    <div class="sidebar-nav">
        @if(auth()->user()->isAdmin())

        <div class="nav-section">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>

        <div class="nav-section">Academic</div>
        <a href="{{ route('admin.batches.index') }}" class="{{ request()->routeIs('admin.batches.*') ? 'active' : '' }}">
            <i class="fas fa-layer-group"></i> Batches
        </a>
        <a href="{{ route('admin.subjects.index') }}" class="{{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}">
            <i class="fas fa-book-open"></i> Subjects
        </a>
        <a href="{{ route('admin.students.index') }}" class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
            <i class="fas fa-user-graduate"></i> Students
        </a>
        <a href="{{ route('admin.lecturers.index') }}" class="{{ request()->routeIs('admin.lecturers.*') ? 'active' : '' }}">
            <i class="fas fa-chalkboard-teacher"></i> Lecturers
        </a>
        <a href="{{ route('admin.timetables.index') }}" class="{{ request()->routeIs('admin.timetables.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> Timetables
        </a>

        <div class="nav-section">Monitoring</div>
        <a href="{{ route('admin.alerts') }}" class="{{ request()->routeIs('admin.alerts') ? 'active' : '' }}">
            <i class="fas fa-exclamation-triangle"></i> Low Attendance
        </a>
        <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i> Reports & PDF
        </a>
        <div class="nav-section">Account</div>
    <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
        <i class="fas fa-user-circle"></i> My Profile
    </a>

        @else

        <div class="nav-section">Overview</div>
        <a href="{{ route('lecturer.dashboard') }}" class="{{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>

        <div class="nav-section">Schedule</div>
        <a href="{{ route('lecturer.timetable') }}" class="{{ request()->routeIs('lecturer.timetable') ? 'active' : '' }}">
            <i class="fas fa-calendar-week"></i> My Timetable
        </a>
            <div class="nav-section">Account</div>
    <a href="{{ route('lecturer.notifications') }}" class="{{ request()->routeIs('lecturer.notifications') ? 'active' : '' }}">
        <i class="fas fa-bell"></i> Notifications
        @php
            $myNotifCount = \App\Models\Subject::where('user_id', auth()->id())->with('sessions')->get()->sum(function($s) {
                if($s->sessions->count() === 0) return 0;
            $students = \App\Models\Student::where('batch_id',$s->batch_id)->where('status','active')->get();
            $count = 0;
            foreach($students as $stu) {
                $present = \App\Models\Attendance::whereIn('session_id',$s->sessions->pluck('id'))->where('student_id',$stu->id)->whereIn('status',['Present','Late'])->count();
                $pct = round(($present/$s->sessions->count())*100,1);
                if($pct < 80) $count++;
            }
            return $count;
        });
    @endphp
    @if($myNotifCount > 0)
        <span style="background:#ef4444;color:white;font-size:10px;font-weight:700;padding:2px 6px;border-radius:10px;margin-left:auto;">
            {{ $myNotifCount }}
        </span>
    @endif
</a>
<a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
    <i class="fas fa-user-circle"></i> My Profile
</a>
<a href="#" onclick="event.preventDefault(); document.getElementById('lecturer-logout').submit();"
    style="color:rgba(255,255,255,0.7);">
    <i class="fas fa-sign-out-alt"></i> Sign Out
</a>
<form id="lecturer-logout" method="POST" action="{{ route('logout') }}" style="display:none;">
    @csrf
</form>

        <div class="nav-section">Attendance</div>
        @foreach(\App\Models\Subject::where('user_id', auth()->id())->with('batch')->get() as $navSubject)
        <a href="{{ route('lecturer.attendance.history', $navSubject) }}"
            class="{{ request()->route('subject') && request()->route('subject')->id === $navSubject->id ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> {{ Str::limit($navSubject->name, 20) }}
        </a>
        @endforeach

        @endif
    </div>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ Str::limit(auth()->user()->name, 18) }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        @if(auth()->user()->isAdmin())
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt me-2"></i>Sign Out
            </button>
        </form>
        @endif
    </div>
</div>

<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <h5>@yield('title', 'Dashboard')</h5>
            <p>@yield('subtitle', 'SLIATE Dambawela · HNDIT Programme')</p>
        </div>
        <div class="topbar-right">
            @if(auth()->user()->isAdmin())
            <div style="position:relative;">
                <button class="notif-btn" onclick="toggleNotif()" id="notifBtn">
                    <i class="fas fa-bell"></i>
                    @php
                        $lowCount = 0;
                        foreach(\App\Models\Subject::with('sessions')->get() as $subj) {
                            if($subj->sessions->count() === 0) continue;
                            $students = \App\Models\Student::where('batch_id', $subj->batch_id)->where('status','active')->get();
                            foreach($students as $stu) {
                                $present = \App\Models\Attendance::whereIn('session_id', $subj->sessions->pluck('id'))->where('student_id',$stu->id)->whereIn('status',['Present','Late'])->count();
                                $pct = round(($present/$subj->sessions->count())*100,1);
                                if($pct < 80) $lowCount++;
                            }
                        }
                    @endphp
                    @if($lowCount > 0)
                        <span class="notif-badge">{{ $lowCount > 9 ? '9+' : $lowCount }}</span>
                    @endif
                </button>

                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-header">
                        <i class="fas fa-bell me-2 text-primary"></i>Notifications
                        @if($lowCount > 0)<span class="badge bg-danger ms-2">{{ $lowCount }}</span>@endif
                    </div>
                    @if($lowCount > 0)
                        <div class="notif-item">
                            <div class="notif-icon" style="background:#fee2e2;">
                                <i class="fas fa-exclamation-triangle" style="color:#dc2626;"></i>
                            </div>
                            <div>
                                <div class="notif-text"><strong>{{ $lowCount }} student(s)</strong> are below 80% attendance threshold</div>
                                <div class="notif-time"><a href="{{ route('admin.alerts') }}" style="color:#3b82f6;">View all alerts →</a></div>
                            </div>
                        </div>
                    @else
                        <div class="notif-empty"><i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>No alerts right now!</div>
                    @endif
                </div>
            </div>
            @endif
            <div class="date-badge">
                <i class="fas fa-calendar-alt"></i>{{ now()->format('d M Y') }}
            </div>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success mb-4 d-flex align-items-center gap-2">
                <i class="fas fa-check-circle"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-4 d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>{{ session('error') }}
            </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleNotif() {
    document.getElementById('notifDropdown').classList.toggle('show');
}
document.addEventListener('click', function(e) {
    if (!document.getElementById('notifBtn')?.contains(e.target)) {
        document.getElementById('notifDropdown')?.classList.remove('show');
    }
});
</script>
@yield('scripts')
</body>
</html>