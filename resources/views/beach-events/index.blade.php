<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beach Events - Paradise Island</title>
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('theme-parks.index') }}">🎢 Theme Parks</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('beach-events.index') }}">🏖️ Beach Events</a></li>
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
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1>🏖️ Paradise Beach Events</h1>
        <div class="alert alert-success">
            <strong>No Hotel Booking Required:</strong> Beach events can be booked independently!
        </div>
        
        <div class="row">
            @forelse($beachEvents as $event)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            @php
                                $badgeClass = match($event->type) {
                                    'sports' => 'bg-info',
                                    'music' => 'bg-warning',
                                    'adventure' => 'bg-success',
                                    'cultural' => 'bg-purple',
                                    'dining' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} mb-2">{{ ucfirst($event->type) }}</span>
                            <h5 class="card-title">{{ $event->name }}</h5>
                            <p class="card-text">{{ $event->description }}</p>
                            <p class="text-muted">📍 {{ $event->location }}</p>
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('M j, Y') }}</p>
                            <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}</p>
                            <p><strong>Price:</strong> ${{ number_format($event->price, 2) }}</p>
                            <p><strong>Capacity:</strong> {{ $event->capacity }} {{ $event->type === 'sports' ? 'participants' : 'guests' }}</p>
                            @if($event->requirements)
                                <p><small class="text-muted">{{ $event->requirements }}</small></p>
                            @endif
                            @auth
                                <a href="{{ route('beach-events.book', $event) }}" class="btn btn-{{ str_replace('bg-', '', $badgeClass) }}">
                                    {{ $event->type === 'sports' ? 'Join Tournament' : ($event->type === 'music' ? 'Get Tickets' : 'Book Tour') }}
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-{{ str_replace('bg-', '', $badgeClass) }}">Login to Book</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        <h5>No Beach Events Available</h5>
                        <p>Check back soon for exciting beach events and activities!</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="alert alert-info mt-4">
            <h5>🌊 About Beach Events</h5>
            <p>Our beach events are designed to give you the authentic Paradise Island experience. From competitive sports to cultural celebrations, there's something for everyone!</p>
            <ul>
                <li>All equipment included for activities</li>
                <li>Professional guides and instructors</li>
                <li>Safety briefings provided</li>
                <li>Refreshments available for purchase</li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
