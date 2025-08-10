<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">🏝️ Paradise Island</a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('hotel-bookings.index') }}">My Bookings</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">My Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1>Welcome, {{ Auth::user()->name }}!</h1>
                <p class="lead">Your Paradise Island Dashboard</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>🏨 Hotels</h5>
                    </div>
                    <div class="card-body">
                        <p>Browse and book hotels on Paradise Island.</p>
                        <a href="{{ route('hotels.index') }}" class="btn btn-primary">View Hotels</a>
                    </div>
                </div>
            </div>

            @php
                $hasHotelBooking = Auth::user()->hotelBookings()->where('status', 'confirmed')->exists();
            @endphp

            <div class="col-md-4">
                <div class="card {{ !$hasHotelBooking ? 'opacity-50' : '' }}">
                    <div class="card-header">
                        <h5>⛴️ Ferries</h5>
                    </div>
                    <div class="card-body">
                        @if($hasHotelBooking)
                            <p>Book ferry tickets to the theme park island.</p>
                            <a href="{{ route('ferries.index') }}" class="btn btn-primary">View Ferries</a>
                        @else
                            <p class="text-muted">Book a hotel first to access ferry services.</p>
                            <button class="btn btn-secondary" disabled>Requires Hotel Booking</button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card {{ !$hasHotelBooking ? 'opacity-50' : '' }}">
                    <div class="card-header">
                        <h5>🎢 Theme Parks</h5>
                    </div>
                    <div class="card-body">
                        @if($hasHotelBooking)
                            <p>Experience thrilling rides and attractions.</p>
                            <a href="{{ route('theme-parks.index') }}" class="btn btn-primary">View Parks</a>
                        @else
                            <p class="text-muted">Book a hotel first to access theme parks.</p>
                            <button class="btn btn-secondary" disabled>Requires Hotel Booking</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>🏖️ Beach Events</h5>
                    </div>
                    <div class="card-body">
                        <p>Join beach parties and events on the main island.</p>
                        <a href="{{ route('beach-events.index') }}" class="btn btn-primary">View Events</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>🗺️ Island Map</h5>
                    </div>
                    <div class="card-body">
                        <p>Explore the interactive map of Paradise Island.</p>
                        <a href="{{ route('map') }}" class="btn btn-primary">View Map</a>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->role !== 'visitor')
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card bg-warning">
                        <div class="card-header">
                            <h5>Business Owner Panel</h5>
                        </div>
                        <div class="card-body">
                            <p>You have a <strong>{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</strong> account.</p>
                            <p>Business management features coming soon!</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($hasHotelBooking)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-success">
                        <h6>✅ Hotel Booking Confirmed</h6>
                        <p class="mb-0">You have an active hotel booking. You can now access ferry services and theme parks!</p>
                    </div>
                </div>
            </div>
        @else
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h6>ℹ️ Start Your Paradise Adventure</h6>
                        <p class="mb-0">Book a hotel on Paradise Island to unlock ferry services and theme park access!</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
