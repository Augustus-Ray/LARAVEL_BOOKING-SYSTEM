<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .auth-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .admin-sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .admin-btn {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            text-align: center;
            transition: transform 0.2s;
        }
        .admin-btn:hover {
            transform: translateY(-2px);
            color: white;
        }
        .super-admin-btn {
            background: linear-gradient(135deg, #8e44ad 0%, #663399 100%);
        }
    </style>
</head>
<body class="auth-card">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <!-- Admin Sidebar -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="admin-sidebar p-4">
                    <h5 class="text-center mb-4">🔐 Admin Access</h5>
                    
                    <a href="{{ route('admin.login') }}" class="admin-btn">
                        <i class="fas fa-user-shield"></i> Admin Login
                    </a>
                    
                    <a href="{{ route('super-admin.login') }}" class="admin-btn super-admin-btn">
                        <i class="fas fa-crown"></i> Super Admin Login
                    </a>
                    
                    <hr class="my-3">
                    
                    <div class="text-center">
                        <small class="text-muted">
                            <strong>Super Admin:</strong><br>
                            superadmin@paradiseisland.com<br>
                            Password: password123<br><br>
                            
                            <strong>General Admin:</strong><br>
                            admin@paradiseisland.com<br>
                            Password: admin123<br><br>
                            
                            <strong>Hotel Manager:</strong><br>
                            hotel@paradiseisland.com<br>
                            Password: hotel123<br><br>
                            
                            <strong>Ferry Operator:</strong><br>
                            ferry@paradiseisland.com<br>
                            Password: ferry123<br><br>
                            
                            <strong>Park Manager:</strong><br>
                            themepark@paradiseisland.com<br>
                            Password: park123<br><br>
                            
                            <strong>Beach Organizer:</strong><br>
                            beach@paradiseisland.com<br>
                            Password: beach123
                        </small>
                    </div>
                </div>
            </div>

            <!-- User Login Form -->
            <div class="col-lg-6 col-md-8">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <h1 class="text-white display-6">🏝️ Paradise Island</h1>
                        <p class="text-white-50">Welcome Back!</p>
                    </a>
                </div>

                <div class="card card-custom">
                    <div class="card-body p-4">
                        <h4 class="card-title text-center mb-4">Sign In</h4>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">
                                    Remember me
                                </label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-custom text-white">
                                    Sign In
                                </button>
                            </div>

                            <div class="text-center">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-muted small text-decoration-none">
                                        Forgot your password?
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <p class="text-white-50 small">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-white text-decoration-none fw-bold">Create Account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
