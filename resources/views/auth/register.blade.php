<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SBT Warehouse Inventory</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card" style="max-width: 500px;">
            <div class="login-logo">
                <img src="/assets/images/sbt-logo.png" alt="SBT Logo" style="height: 60px; width: auto; margin-bottom: 15px;">
                <h1>SBT Constructions</h1>
                <p>Account Registration</p>
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
                </div>
            @else
                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="full_name" class="form-control" required value="{{ old('full_name') }}">
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Username *</label>
                                <input type="text" name="username" class="form-control" required value="{{ old('username') }}">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Password *</label>
                                <input type="password" name="password" class="form-control" required minlength="6">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" required minlength="6">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Requested Role *</label>
                        <select name="requested_role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option value="user" {{ old('requested_role') == 'user' ? 'selected' : '' }}>User (Warehouse Staff)</option>
                            <option value="admin" {{ old('requested_role') == 'admin' ? 'selected' : '' }}>Admin (Manager) - Requires Approval</option>
                            <option value="super_admin" {{ old('requested_role') == 'super_admin' ? 'selected' : '' }}>Super Admin (Owner) - Requires Approval</option>
                        </select>
                        <small style="color: #666; font-size: 12px;">All accounts require approval from existing Super Admin</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-lg">Create Account</button>
                </form>
                <div style="margin-top: 20px; text-align: center;">
                    <p style="color: #666; font-size: 14px;">Already have an account?</p>
                    <a href="{{ route('login') }}" class="btn btn-secondary">Login Here</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
