<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Parks - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🏝️ Paradise Island</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('hotels.index') }}">🏨 Hotels</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('ferries.index') }}">⛴️ Ferries</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('theme-parks.index') }}">🎢 Theme Parks</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('beach-events.index') }}">🏖️ Beach Events</a></li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('hotel-bookings.index') }}">My Bookings</a></li>
                                <li><a class="dropdown-item" href="#">My Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-success text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">🎢 Adventure Theme Parks</h1>
            <p class="lead">Experience thrilling rides and magical adventures on Paradise Island</p>
        </div>
    </div>

    <!-- Theme Parks Section -->
    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @auth
            @if($hasActiveBooking)
                <!-- Theme Parks Available -->
                <div class="row">
                    @forelse($themeparks as $park)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow">
                                @if($park->image_url)
                                    <img src="{{ $park->image_url }}" class="card-img-top" alt="{{ $park->name }}" style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">🎢 {{ $park->name }}</h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $park->description }}</p>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <strong>📍 Location:</strong><br>
                                            <small class="text-muted">{{ $park->location }}</small><br>
                                            <strong>🕘 Hours:</strong><br>
                                            <small class="text-muted">{{ $park->opening_time }} - {{ $park->closing_time }}</small>
                                        </div>
                                        <div class="col-6">
                                            <strong>🎮 Activities:</strong><br>
                                            @if($park->activities->count() > 0)
                                                @foreach($park->activities->take(2) as $activity)
                                                    <small class="text-muted">• {{ $activity->name }}</small><br>
                                                @endforeach
                                                @if($park->activities->count() > 2)
                                                    <small class="text-muted">+ {{ $park->activities->count() - 2 }} more</small>
                                                @endif
                                            @else
                                                <small class="text-muted">Coming soon...</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="h5 text-primary mb-0">${{ number_format($park->entry_price, 2) }}</span>
                                        <small class="text-muted">per person</small>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('theme-parks.show', $park) }}" class="btn btn-outline-primary">View Details</a>
                                        <a href="{{ route('parks.book', $park) }}" class="btn btn-primary">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <h5>🎢 No Theme Parks Available</h5>
                                <p>Theme parks are currently not available. Please check back later!</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Individual Activities -->
                <div class="mt-5">
                    <h3 class="mb-4">🎯 Individual Activities</h3>
                    <div class="row">
                        @php
                            $allActivities = collect();
                            foreach($themeparks as $park) {
                                $allActivities = $allActivities->merge($park->activities);
                            }
                        @endphp
                        
                        @forelse($allActivities as $activity)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $activity->name }}</h6>
                                        <p class="card-text">{{ $activity->description }}</p>
                                        <p><strong>Price:</strong> ${{ number_format($activity->price, 2) }} | <strong>Duration:</strong> {{ $activity->duration_minutes }} mins</p>
                                        <small class="text-muted">📍 {{ $activity->themePark->name }}</small><br>
                                        @auth
                                            @if($hasActiveBooking)
                                                <a href="{{ route('activities.book', $activity) }}" class="btn btn-outline-primary btn-sm mt-2">Book Activity</a>
                                            @else
                                                <button class="btn btn-outline-secondary btn-sm mt-2" disabled>Book Hotel First</button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm mt-2">Login to Book</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <h5>🎯 No Activities Available</h5>
                                    <p>Activities are currently not available. Please check back later!</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <!-- Hotel Booking Required -->
                <div class="text-center">
                    <div class="alert alert-warning">
                        <h5>🏨 Hotel Booking Required</h5>
                        <p>You must have an active hotel booking to access theme parks and activities.</p>
                        <a href="{{ route('hotels.index') }}" class="btn btn-primary">Book a Hotel First</a>
                    </div>
                </div>
            @endif
        @else
            <!-- Guest Message -->
            <div class="text-center">
                <div class="alert alert-info">
                    <h5>Welcome to Paradise Island Theme Parks! 🎢</h5>
                    <p>Please log in to view available theme parks and book tickets.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                </div>
            </div>

            <!-- Preview for Guests -->
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-4">🎢 Available Theme Parks</h3>
                </div>
                @forelse($themeparks as $park)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow">
                            @if($park->image_url)
                                <img src="{{ $park->image_url }}" class="card-img-top" alt="{{ $park->name }}" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">🎢 {{ $park->name }}</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ $park->description }}</p>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>📍 Location:</strong><br>
                                        <small class="text-muted">{{ $park->location }}</small><br>
                                        <strong>🕘 Hours:</strong><br>
                                        <small class="text-muted">{{ $park->opening_time }} - {{ $park->closing_time }}</small>
                                    </div>
                                    <div class="col-6">
                                        <strong>🎮 Activities:</strong><br>
                                        @if($park->activities->count() > 0)
                                            @foreach($park->activities->take(2) as $activity)
                                                <small class="text-muted">• {{ $activity->name }}</small><br>
                                            @endforeach
                                            @if($park->activities->count() > 2)
                                                <small class="text-muted">+ {{ $park->activities->count() - 2 }} more</small>
                                            @endif
                                        @else
                                            <small class="text-muted">Coming soon...</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 text-primary mb-0">${{ number_format($park->entry_price, 2) }}</span>
                                    <small class="text-muted">per person</small>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-primary">Login to Book</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h5>🎢 No Theme Parks Available</h5>
                            <p>Theme parks are currently not available. Please check back later!</p>
                        </div>
                    </div>
                @endforelse
            </div>
        @endauth
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Paradise Island Resort. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
