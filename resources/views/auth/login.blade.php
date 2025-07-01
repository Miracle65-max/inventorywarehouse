<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SBT Warehouse Inventory</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="images/png" href="/assets/images/sbt-logo.png">
    <style>
        body {
            background: #f3f4f6;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .login-split-container { 
            display: flex; 
            min-height: 100vh; 
            background: #f3f4f6; 
        }
        .login-image-side { flex: 1; background: #D9D9D9; position: relative; display: flex; align-items: center; justify-content: center; }
        .login-form-side { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; background: white; }
        .login-card { width: 100%; max-width: 400px; padding: 2rem; background: white; border-radius: 0.75rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .login-logo { text-align: center; margin-bottom: 2rem; }
        .login-logo img { width: 500px; height: auto; margin-bottom: 1rem; }
        .login-logo h1 { margin: 0; color: #111827; font-size: 1.875rem; font-weight: 600; }
        .login-logo p { color: #6b7280; margin: 0.5rem 0; font-size: 0.875rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; color: #374151; font-size: 0.875rem; font-weight: 500; }
        .form-control { width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; transition: border-color 0.15s ease-in-out; }
        .form-control:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        .btn { padding: 0.75rem 1.5rem; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; transition: all 0.15s ease-in-out; }
        .btn-primary { background: #2563eb; color: white; border: none; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { background: #6b7280; color: white; border: none; text-decoration: none; display: inline-block; }
        .btn-secondary:hover { background: #4b5563; }
        .alert { padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; font-size: 0.875rem; }
        .alert-danger { background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; }
        .w-100 { width: 100%; }
        .btn-lg { padding: 0.875rem 1.5rem; font-size: 1rem; }
        .default-accounts { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb; text-align: center; font-size: 0.75rem; color: #6b7280; }
        @media (max-width: 768px) { .login-split-container { flex-direction: column; } .login-image-side { display: none; } .login-form-side { padding: 1.5rem; } .login-card { box-shadow: none; } }
    </style>
</head>
<body>
    <div class="login-split-container">
        <div class="login-image-side">
            <div style="height: 100%; display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('assets/images/sbt-logo.png') }}" alt="SBT Logo" style="max-width: 500px; height: auto;">
            </div>
        </div>
        <div class="login-form-side">
            <div class="login-card">
                <div class="login-logo">
                    <h1>Welcome Back</h1>
                    <p>Sign in to your account</p>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required value="{{ old('username') }}" placeholder="Enter your username">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 btn-lg">Sign in to Account</button>
                </form>
                <div style="margin-top: 1.5rem; text-align: center;">
                    <p style="color: #6b7280; font-size: 0.875rem;">Don't have an account?</p>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Request Account</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
