<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAMS | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    * { 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        min-height: 100vh;
        display: flex;
        overflow-y: auto;
    }

    .left-panel {
        width: 58%;
        position: relative;
        background: url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1400&q=80') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .left-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(10,30,90,0.88) 0%, rgba(20,70,160,0.80) 100%);
        backdrop-filter: blur(4px);
    }

    .left-content {
        position: relative;
        z-index: 2;
        color: white;
        padding: 50px;
        text-align: center;
    }

    .institute-badge {
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 50px;
        padding: 7px 18px;
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 24px;
        display: inline-block;
        backdrop-filter: blur(10px);
    }

    .left-content h1 {
        font-size: 38px;
        font-weight: 900;
        line-height: 1.15;
        margin-bottom: 14px;
        text-shadow: 0 2px 15px rgba(0,0,0,0.4);
    }

    .left-content h1 span {
        color: #60a5fa;
    }

    .left-content p {
        font-size: 14px;
        opacity: 0.82;
        line-height: 1.7;
        margin-bottom: 34px;
        max-width: 380px;
        margin-left: auto;
        margin-right: auto;
    }

    .stats-row {
        display: flex;
        gap: 14px;
        justify-content: center;
    }

    .stat-card {
        background: rgba(255,255,255,0.10);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 16px;
        padding: 16px 22px;
        backdrop-filter: blur(12px);
        text-align: center;
        min-width: 95px;
    }

    .stat-card .number {
        font-size: 24px;
        font-weight: 900;
        color: #60a5fa;
    }

    .stat-card .label {
        font-size: 10px;
        opacity: 0.75;
        margin-top: 4px;
        letter-spacing: 0.5px;
    }

    /* RIGHT PANEL */
    .right-panel {
        width: 42%;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .login-card {
        background: white;
        border-radius: 24px;
        padding: 18px 28px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.10);
        border: 1px solid #e2eaf5;
    }

    .logo-area {
        text-align: center;
        margin-bottom: 12px;
    }

    .logo-circle {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #0f2864, #3b82f6);
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        box-shadow: 0 8px 22px rgba(59,130,246,0.35);
    }

    .logo-circle i {
        font-size: 24px;
        color: white;
    }

    .login-card h2 {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .login-card .subtitle {
        font-size: 13px;
        color: #94a3b8;
    }

    /* Role Selector */
    .role-selector {
        display: flex;
        gap: 8px;
        margin-bottom: 10px;
    }

    .role-btn {
        flex: 1;
        padding: 10px 8px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
    }

    .role-btn i {
        display: block;
        font-size: 18px;
        margin-bottom: 4px;
        color: #94a3b8;
    }

    .role-btn.active {
        border-color: #3b82f6;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .role-btn.active i {
        color: #3b82f6;
    }

    .role-btn:hover:not(.active) {
        border-color: #cbd5e1;
        background: #f8fafc;
    }

    .form-label {
        font-size: 12px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 5px;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        background: #f8fafc;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: #3b82f6;
        background: white;
        box-shadow: 0 0 0 4px rgba(59,130,246,0.10);
    }

    .input-group .form-control {
        border-right: none;
        border-radius: 10px 0 0 10px;
    }

    .input-group-text {
        border: 2px solid #e2e8f0;
        border-left: none;
        border-radius: 0 10px 10px 0;
        background: #f8fafc;
        color: #94a3b8;
        cursor: pointer;
    }

    .input-group .form-control:focus + .input-group-text {
        border-color: #3b82f6;
    }

    .btn-login {
        background: linear-gradient(135deg, #0f2864, #3b82f6);
        border: none;
        border-radius: 12px;
        padding: 11px;
        font-size: 14px;
        font-weight: 700;
        color: white;
        width: 100%;
        margin-top: 4px;
        transition: all 0.25s;
        box-shadow: 0 6px 20px rgba(59,130,246,0.35);
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(59,130,246,0.45);
        color: white;
    }

    .alert-danger {
        border-radius: 10px;
        font-size: 12px;
        border: none;
        background: #fef2f2;
        color: #dc2626;
        padding: 10px 14px;
    }

    .footer-text {
        text-align: center;
        font-size: 10px;
        color: #94a3b8;
        margin-top: 18px;
        line-height: 1.6;
    }

    .mb-3 {
        margin-bottom: 12px !important;
    }

    @media (max-width: 768px) {
        .left-panel {
            display: none;
        }

        .right-panel {
            width: 100%;
        }

        .login-card {
            max-width: 100%;
        }
    }
</style>
</head>
<body>

<!-- LEFT PANEL -->
<div class="left-panel">
    <div class="left-overlay"></div>
    <div class="left-content">
        <div class="institute-badge">
            <i class="fas fa-university me-2"></i>SLIATE Dambawela
        </div>
        <h1>Student <span>Attendance</span><br>Management System</h1>
        <p>A centralized digital platform for managing student attendance across the HNDIT programme with real-time tracking and automated reporting.</p>
        <div class="stats-row">
            <div class="stat-card">
                <div class="number">2</div>
                <div class="label">Batches</div>
            </div>
            <div class="stat-card">
                <div class="number">90+</div>
                <div class="label">Students</div>
            </div>
            <div class="stat-card">
                <div class="number">80%</div>
                <div class="label">Min. Attendance</div>
            </div>
            <div class="stat-card">
                <div class="number">2</div>
                <div class="label">User Roles</div>
            </div>
        </div>
    </div>
</div>

<!-- RIGHT PANEL -->
<div class="right-panel">
    <div class="login-card">

        <div class="logo-area">
            <div class="logo-circle">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h2>Welcome Back</h2>
            <p class="subtitle">Sign in to your SAMS account</p>
        </div>

    @if ($errors->any())
    <div class="alert alert-danger mb-3">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ $errors->first('email') ?? 'Invalid credentials. Please try again.' }}
    </div>
@endif

        <!-- Role Selector -->
        <p style="font-size:13px;font-weight:700;color:#374151;margin-bottom:10px;">Select Your Role</p>
        <div class="role-selector">
            <div class="role-btn active" id="adminBtn" onclick="selectRole('admin')">
                <i class="fas fa-user-shield"></i>Administrator
            </div>
            <div class="role-btn" id="lecturerBtn" onclick="selectRole('lecturer')">
                <i class="fas fa-chalkboard-teacher"></i>Lecturer
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="selected_role" id="selectedRole" value="admin">

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-envelope me-2 text-muted"></i>Email Address</label>
                <input type="email" name="email" class="form-control"
                    placeholder="Enter your email address"
                    value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fas fa-lock me-2 text-muted"></i>Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="passwordField"
                        class="form-control" placeholder="Enter your password" required>
                    <span class="input-group-text" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" style="font-size:13px;color:#64748b;" for="remember">
                    Keep me signed in
                </label>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In to SAMS
            </button>
        </form>

        <div class="footer-text">
            Department of Information Technology<br>
            SLIATE Dambawela &mdash; HNDIT Programme &copy; {{ date('Y') }}
        </div>

    </div>
</div>

<script>
    function selectRole(role) {
        document.getElementById('selectedRole').value = role;
        document.getElementById('adminBtn').classList.remove('active');
        document.getElementById('lecturerBtn').classList.remove('active');
        document.getElementById(role === 'admin' ? 'adminBtn' : 'lecturerBtn').classList.add('active');
    }
    function togglePassword() {
        const field = document.getElementById('passwordField');
        const icon = document.getElementById('eyeIcon');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
</body>
</html>