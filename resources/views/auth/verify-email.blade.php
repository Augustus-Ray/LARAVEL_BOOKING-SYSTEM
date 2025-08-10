<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body class="auth-card">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <h1 class="text-white display-6">🏝️ Paradise Island</h1>
                        <p class="text-white-50">Verify Your Email</p>
                    </a>
                </div>

                <div class="card card-custom">
                    <div class="card-body p-4">
                        <h4 class="card-title text-center mb-3">Email Verification</h4>
                        
                        <p class="text-muted text-center small mb-4">
                            Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you. If you didn't receive the email, we'll gladly send you another.
                        </p>

                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success" role="alert">
                                A new verification link has been sent to the email address you provided during registration.
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            <form method="POST" action="{{ route('verification.send') }}" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-custom text-white">
                                    Resend Verification Email
                                </button>
                            </form>

                            <form method="POST" action="{{ route('logout') }}" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
