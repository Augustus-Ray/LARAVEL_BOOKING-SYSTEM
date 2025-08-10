<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .auth-card {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            min-height: 100vh;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
        }
        .form-control:focus {
            border-color: #e74c3c;
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
        }
    </style>
</head>
<body class="auth-card">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <h1 class="text-white display-6"><i class="fas fa-user-shield"></i> Admin Portal</h1>
                        <p class="text-white-50">Paradise Island Administration</p>
                    </a>
                </div>

                <div class="card card-custom">
                    <div class="card-body p-4">
                        <h4 class="card-title text-center mb-4">Admin Sign In</h4>

                        <!-- Login Credentials Info -->
                        <div class="alert alert-info" role="alert">
                            <h6><i class="fas fa-user-shield me-2"></i>Default Login Credentials:</h6>
                            <strong>Email:</strong> admin@paradiseisland.com<br>
                            <strong>Password:</strong> admin123
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.login.submit') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Admin Email</label>
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

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-custom text-white">
                                    <i class="fas fa-sign-in-alt me-2"></i>Admin Sign In
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <p class="text-white-50 small">
                        <a href="{{ route('login') }}" class="text-white text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>Back to User Login
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
