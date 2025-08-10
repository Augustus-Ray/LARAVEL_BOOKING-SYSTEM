<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferry Services - Paradise Island</title>
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('ferries.index') }}">⛴️ Ferries</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('theme-parks.index') }}">🎢 Theme Parks</a></li>
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
    <div class="bg-info text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">⛴️ Ferry Services</h1>
            <p class="lead">Regular ferry services connecting Paradise Island to the mainland and theme park</p>
        </div>
    </div>

    <!-- Ferry Services Section -->
    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            @forelse($ferries as $ferry)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">⛴️ {{ $ferry->name }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ $ferry->description }}</p>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Route:</strong><br>
                                    <small class="text-muted">{{ $ferry->departure_location }} → {{ $ferry->arrival_location }}</small>
                                </div>
                                <div class="col-6">
                                    <strong>Capacity:</strong><br>
                                    <small class="text-muted">{{ $ferry->capacity }} passengers</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Departure:</strong><br>
                                    <small class="text-muted">{{ $ferry->departure_time }}</small>
                                </div>
                                <div class="col-6">
                                    <strong>Arrival:</strong><br>
                                    <small class="text-muted">{{ $ferry->arrival_time }}</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 text-success mb-0">${{ $ferry->price }}</span>
                                <small class="text-muted">per person</small>
                            </div>
                            
                            @auth
                                @if(Auth::user()->hasValidHotelBooking())
                                    <a href="{{ route('ferries.show', $ferry) }}" class="btn btn-info">
                                        View Details & Book
                                    </a>
                                @else
                                    <div class="alert alert-warning">
                                        <small>⚠️ You must book a hotel first before purchasing ferry tickets.</small>
                                    </div>
                                    <a href="{{ route('hotels.index') }}" class="btn btn-outline-info">
                                        Book Hotel First
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-info">
                                    Login to Book
                                </a>
                            @endauth
                        </div>
                        <div class="card-footer text-muted">
                            <small>🚢 Daily service available</small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <h4>🚧 Ferry services are currently being updated</h4>
                        <p>Check back soon for available ferry schedules.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Ferry Information Section -->
        <div class="row mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">🗺️ Ferry Route Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>🏙️ Mainland to Paradise Island</h6>
                                <ul class="list-unstyled">
                                    <li>✅ Departure: Marina Port</li>
                                    <li>✅ Arrival: Paradise Harbor</li>
                                    <li>✅ Duration: 45 minutes</li>
                                    <li>✅ Frequency: Every 2 hours</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>🎢 Paradise Island to Theme Park</h6>
                                <ul class="list-unstyled">
                                    <li>✅ Departure: Paradise Harbor</li>
                                    <li>✅ Arrival: Adventure Bay</li>
                                    <li>✅ Duration: 20 minutes</li>
                                    <li>✅ Frequency: Every hour</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">📋 Important Notes</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li>🏨 <strong>Hotel booking required</strong></li>
                            <li>🎫 <strong>Advance booking recommended</strong></li>
                            <li>🌊 <strong>Weather dependent</strong></li>
                            <li>🧳 <strong>Luggage included</strong></li>
                            <li>♿ <strong>Wheelchair accessible</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Safety Information -->
    <div class="bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h5>🛡️ Safety First</h5>
                    <p class="mb-0">All ferries are equipped with modern safety equipment and are operated by certified captains. Life jackets are provided for all passengers.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white py-4 mt-5">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-4">
                    <h6>🏝️ Paradise Island</h6>
                    <p class="small">Your gateway to adventure</p>
                </div>
                <div class="col-md-4">
                    <h6>📞 Contact</h6>
                    <p class="small">Ferry Hotline: +1-800-FERRY<br>Email: ferries@paradise-island.com</p>
                </div>
                <div class="col-md-4">
                    <h6>🕒 Operating Hours</h6>
                    <p class="small">Daily: 6:00 AM - 10:00 PM<br>Last departure: 9:00 PM</p>
                </div>
            </div>
            <hr>
            <p class="mb-0">&copy; 2024 Paradise Island Resort. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>