<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">🏝️ Paradise Island</a>
            
            <div class="navbar-nav ms-auto">
                @auth
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('hotel-bookings.index') }}">My Bookings</a></li>
                            <li><a class="dropdown-item active" href="{{ route('profile.edit') }}">My Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center mb-4">
                    <h1 class="me-3">👤 My Profile</h1>
                    <span class="badge bg-success">Active Member</span>
                </div>
            </div>
        </div>

        @if(session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Profile updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('status') === 'password-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Password updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Profile Information -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📝 Profile Information</h5>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Account Statistics -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">📊 Account Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-primary mb-1">{{ auth()->user()->hotelBookings()->count() }}</h3>
                                    <small class="text-muted">Hotel Bookings</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-info mb-1">{{ auth()->user()->ferryTickets()->count() }}</h3>
                                    <small class="text-muted">Ferry Tickets</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-success mb-1">{{ auth()->user()->parkTickets()->count() }}</h3>
                                    <small class="text-muted">Theme Park Visits</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h3 class="text-warning mb-1">{{ auth()->user()->beachTickets()->count() }}</h3>
                                    <small class="text-muted">Beach Events</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="text-center">
                            <p class="mb-2"><strong>Member Since:</strong> {{ auth()->user()->created_at->format('M j, Y') }}</p>
                            <p class="mb-0"><strong>Last Login:</strong> {{ auth()->user()->updated_at->diffForHumans() }}</p>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('hotel-bookings.index') }}" class="btn btn-primary btn-sm me-2">View My Bookings</a>
                            <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary btn-sm">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Update Password -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">🔒 Update Password</h5>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">⚙️ Account Settings</h5>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">← Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
