<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paradise Island Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3') center/cover;
            color: white;
            min-height: 70vh;
            display: flex;
            align-items: center;
        }
        .feature-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .ad-banner {
            background: linear-gradient(45deg, #007bff, #28a745);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .booking-flow {
            background: #f8f9fa;
            padding: 40px 0;
        }
        .step-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin: 0 auto 15px;
        }
        .step-arrow {
            font-size: 30px;
            color: #007bff;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🏝️ Paradise Island</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('hotels.index') }}">🏨 Hotels</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ferries.index') }}">⛴️ Ferries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('theme-parks.index') }}">🎢 Theme Parks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('beach-events.index') }}">🏖️ Beach Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('map') }}">🗺️ Island Map</a>
                    </li>
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
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">My Profile</a></li>
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
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">Welcome to Paradise Island</h1>
                    <p class="lead mb-4">Your ultimate destination for island adventures, theme park thrills, and unforgettable memories!</p>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">Get Started</a>
                        <a href="{{ route('hotels.index') }}" class="btn btn-outline-light btn-lg">Browse Hotels</a>
                    @else
                        <a href="{{ route('hotels.index') }}" class="btn btn-light btn-lg me-3">Book Now</a>
                        <a href="{{ route('map') }}" class="btn btn-outline-light btn-lg">Explore Map</a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Flow Section -->
    <section class="booking-flow">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Simple 3-Step Booking Process</h2>
                <p class="text-muted">Follow our easy booking flow to ensure the best experience</p>
            </div>
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <div class="step-circle">1</div>
                    <h5>Book Hotel</h5>
                    <p class="text-muted">Start with booking accommodation on the main island</p>
                </div>
                <div class="col-md-1 text-center d-none d-md-block">
                    <div class="step-arrow">→</div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="step-circle">2</div>
                    <h5>Ferry & Parks</h5>
                    <p class="text-muted">Book ferry tickets and theme park access</p>
                </div>
                <div class="col-md-1 text-center d-none d-md-block">
                    <div class="step-arrow">→</div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="step-circle">3</div>
                    <h5>Activities</h5>
                    <p class="text-muted">Add beach events and park activities</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Hotels -->
    @if($featuredHotels->count() > 0)
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Featured Hotels</h2>
                <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary">View All Hotels</a>
            </div>
            <div class="row">
                @foreach($featuredHotels as $hotel)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card feature-card h-100">
                        @if($hotel->image_url)
                            <img src="{{ $hotel->image_url }}" class="card-img-top" alt="{{ $hotel->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $hotel->name }}</h5>
                            <p class="card-text">{{ Str::limit($hotel->description, 100) }}</p>
                            <p class="text-muted">📍 {{ $hotel->location }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-primary">${{ $hotel->price_per_night }}/night</strong>
                                <a href="{{ route('hotels.show', $hotel) }}" class="btn btn-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Theme Parks Section -->
    @if($featuredParks->count() > 0)
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Epic Theme Parks</h2>
                <a href="{{ route('theme-parks.index') }}" class="btn btn-outline-primary">Explore All Parks</a>
            </div>
            <div class="row">
                @foreach($featuredParks as $park)
                <div class="col-lg-4 mb-4">
                    <div class="card feature-card h-100">
                        @if($park->image_url)
                            <img src="{{ $park->image_url }}" class="card-img-top" alt="{{ $park->name }}" style="height: 250px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-primary d-flex align-items-center justify-content-center text-white" style="height: 250px;">
                                <h3>🎢</h3>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $park->name }}</h5>
                            <p class="card-text">{{ Str::limit($park->description, 120) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-success">${{ $park->entry_price }} entry</strong>
                                <a href="{{ route('theme-parks.show', $park) }}" class="btn btn-success btn-sm">Explore Park</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Beach Events -->
    @if($upcomingBeachEvents->count() > 0)
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Upcoming Beach Events</h2>
                <a href="{{ route('beach-events.index') }}" class="btn btn-outline-info">View All Events</a>
            </div>
            <div class="row">
                @foreach($upcomingBeachEvents->take(3) as $event)
                <div class="col-lg-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-info">{{ ucfirst($event->type) }}</span>
                                <small class="text-muted">{{ $event->start_time->format('M j, Y') }}</small>
                            </div>
                            <h5 class="card-title">{{ $event->name }}</h5>
                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                            <p class="text-muted">📍 {{ $event->location }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-info">${{ $event->price }}</strong>
                                <a href="{{ route('beach-events.show', $event) }}" class="btn btn-info btn-sm">Join Event</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Advertisements -->
    @if(isset($activeAds['banner']) && $activeAds['banner']->count() > 0)
    <section class="py-3">
        <div class="container">
            @foreach($activeAds['banner']->take(2) as $ad)
            <div class="ad-banner text-center">
                <h4>{{ $ad->title }}</h4>
                <p class="mb-2">{{ $ad->description }}</p>
                @if($ad->link_url)
                    <a href="{{ $ad->link_url }}" class="btn btn-light">Learn More</a>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Paradise Island Booking</h5>
                    <p>Your gateway to unforgettable island adventures and theme park experiences.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Quick Links</h5>
                    <a href="{{ route('map') }}" class="text-light text-decoration-none me-3">Island Map</a>
                    <a href="{{ route('hotels.index') }}" class="text-light text-decoration-none me-3">Hotels</a>
                    <a href="{{ route('beach-events.index') }}" class="text-light text-decoration-none">Events</a>
                </div>
            </div>
            <hr class="my-3">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Paradise Island Booking System. Built with Laravel.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
