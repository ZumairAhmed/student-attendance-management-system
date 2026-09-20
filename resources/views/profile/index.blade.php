@extends('layouts.app')
@section('title', 'My Profile')
@section('subtitle', 'Manage your account information')
@section('content')

<div class="row g-4">

    {{-- LEFT — Profile Card --}}
    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="mx-auto mb-3"
                style="width:90px;height:90px;background:linear-gradient(135deg,#1a3a8f,#3b82f6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;color:white;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <p class="text-muted mb-2" style="font-size:13px;">{{ $user->email }}</p>
            <span class="badge badge-info px-3 py-2 rounded-pill mb-3">
                {{ ucfirst($user->role) }}
            </span>
            <div style="background:#f8fafc;border-radius:12px;padding:14px;text-align:left;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-phone text-muted" style="width:16px;"></i>
                    <span style="font-size:13px;color:#374151;">{{ $user->phone ?? 'Not set' }}</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fas fa-user-tag text-muted" style="width:16px;"></i>
                    <span style="font-size:13px;color:#374151;">{{ ucfirst($user->status) }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-calendar text-muted" style="width:16px;"></i>
                    <span style="font-size:13px;color:#374151;">
                        Joined {{ $user->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>

            @if($user->isLecturer())
            <div class="mt-3" style="background:#f8fafc;border-radius:12px;padding:14px;text-align:left;">
                <div style="font-size:12px;font-weight:700;color:#64748b;margin-bottom:8px;">
                    ASSIGNED SUBJECTS
                </div>
                @forelse($user->subjects as $subject)
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-book-open text-primary" style="font-size:11px;"></i>
                    <span style="font-size:12px;color:#374151;">{{ $subject->name }}</span>
                </div>
                @empty
                <span style="font-size:12px;color:#94a3b8;">No subjects assigned</span>
                @endforelse
            </div>
            @endif
        </div>
    </div>

    {{-- RIGHT — Edit Forms --}}
    <div class="col-md-8">

        {{-- Update Profile --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-user-edit me-2 text-primary"></i>Update Profile Information
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                <div class="alert alert-success mb-3">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
                @endif
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="07XXXXXXXX">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-lock me-2 text-primary"></i>Change Password
            </div>
            <div class="card-body p-4">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control"
                                placeholder="Enter current password" required>
                            @error('current_password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Min 6 characters" required>
                            @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                class="form-control" placeholder="Repeat new password" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card" style="border-color:#fee2e2;">
            <div class="card-header" style="background:#fef2f2;color:#dc2626;border-color:#fee2e2;">
                <i class="fas fa-exclamation-triangle me-2"></i>Sign Out
            </div>
            <div class="card-body p-4">
                <p style="font-size:13px;color:#64748b;margin-bottom:16px;">
                    Click below to securely sign out of your SAMS account.
                </p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        style="background:linear-gradient(135deg,#ef4444,#dc2626);border:none;border-radius:10px;padding:10px 24px;color:white;font-size:14px;font-weight:600;cursor:pointer;">
                        <i class="fas fa-sign-out-alt me-2"></i>Sign Out of SAMS
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection