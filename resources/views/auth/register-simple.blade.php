<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center" style="min-height: 100vh; align-items: center;">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>🏝️ Join Paradise Island</h4>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Account Type</label>
                                <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="visitor" {{ old('role') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                                    <option value="hotel_owner" {{ old('role') == 'hotel_owner' ? 'selected' : '' }}>Hotel Owner</option>
                                    <option value="ferry_operator" {{ old('role') == 'ferry_operator' ? 'selected' : '' }}>Ferry Operator</option>
                                    <option value="park_owner" {{ old('role') == 'park_owner' ? 'selected' : '' }}>Theme Park Owner</option>
                                    <option value="event_organizer" {{ old('role') == 'event_organizer' ? 'selected' : '' }}>Event Organizer</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number (Optional)</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input id="password_confirmation" type="password" class="form-control" 
                                       name="password_confirmation" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Create Account</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                        </div>

                        <hr>
                        <div class="alert alert-info">
                            <h6>Account Types:</h6>
                            <small>
                                <strong>Visitor:</strong> Book hotels, ferries, and activities<br>
                                <strong>Hotel Owner:</strong> Manage hotel listings<br>
                                <strong>Ferry Operator:</strong> Manage ferry services<br>
                                <strong>Park Owner:</strong> Manage theme parks<br>
                                <strong>Event Organizer:</strong> Manage beach events
                            </small>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">← Back to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
