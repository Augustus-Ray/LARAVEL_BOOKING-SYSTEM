<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🏝️ Paradise Island</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('hotels.index') }}">🏨 Hotels</a>
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

    <!-- Page Header -->
    <div class="bg-primary text-white py-4">
        <div class="container">
            <h1 class="display-5 fw-bold">🏨 Paradise Hotels</h1>
            <p class="lead">Choose your perfect accommodation on Paradise Island</p>
        </div>
    </div>

    <!-- Hotels Listing -->
    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            @forelse($hotels as $hotel)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($hotel->image_url)
                            <img src="{{ $hotel->image_url }}" class="card-img-top" alt="{{ $hotel->name }}" style="height: 250px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                <h2 class="text-muted">🏨</h2>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $hotel->name }}</h5>
                            <p class="card-text">{{ Str::limit($hotel->description, 120) }}</p>
                            <p class="text-muted mb-2">📍 {{ $hotel->location }}</p>
                            
                            @if($hotel->amenities)
                                <div class="mb-3">
                                    @foreach(array_slice(json_decode($hotel->amenities, true) ?: [], 0, 4) as $amenity)
                                        <span class="badge bg-secondary me-1">{{ ucfirst(str_replace('_', ' ', $amenity)) }}</span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <strong class="text-primary h5">${{ $hotel->price_per_night }}</strong>
                                        <small class="text-muted">/night</small>
                                    </div>
                                    <small class="text-success">{{ $hotel->available_rooms }} rooms available</small>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('hotels.show', $hotel) }}" class="btn btn-outline-primary">View Details</a>
                                    @auth
                                        <a href="{{ route('hotels.book', $hotel) }}" class="btn btn-primary">Book Now</a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary">Login to Book</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h3 class="text-muted">No hotels available</h3>
                    <p>Please check back later for available accommodations.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($hotels->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $hotels->links() }}
            </div>
        @endif
    </div>

    <!-- Info Section -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>🎯 Important Notice</h5>
                    <p class="text-muted">Hotel booking is <strong>required</strong> before you can purchase ferry tickets or theme park tickets.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>📞 Need Help?</h5>
                    <p class="text-muted">Contact our reservations team for assistance with your booking or special requests.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>🎁 Special Offers</h5>
                    <p class="text-muted">Check our featured hotels for exclusive deals and package offers.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
