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
        <h1>⛴️ Ferry Services</h1>
        <div class="alert alert-info">
            <strong>Booking Requirements:</strong> To purchase ferry tickets, you must first book a hotel and be logged in.
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">🌊 Island Paradise Ferry</h5>
                        <p class="card-text">Safe and comfortable ferry service connecting Paradise Island to the Theme Park Island. Enjoy scenic ocean views during your 20-minute journey.</p>
                        
                        <h6>Ferry Schedule:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Paradise Island → Theme Park Island</h6>
                                <ul class="list-unstyled">
                                    <li>🕘 8:00 AM</li>
                                    <li>🕙 10:00 AM</li>
                                    <li>🕐 12:00 PM</li>
                                    <li>🕕 2:00 PM</li>
                                    <li>🕗 4:00 PM</li>
                                    <li>🕙 6:00 PM</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success">Theme Park Island → Paradise Island</h6>
                                <ul class="list-unstyled">
                                    <li>🕘 9:00 AM</li>
                                    <li>🕚 11:00 AM</li>
                                    <li>🕐 1:00 PM</li>
                                    <li>🕕 3:00 PM</li>
                                    <li>🕖 5:00 PM</li>
                                    <li>🕘 7:00 PM</li>
                                    <li>🕘 9:00 PM</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6>🎫 Standard Ferry Ticket</h6>
                            </div>
                            <div class="card-body">
                                <h4 class="text-primary">$15.00</h4>
                                <p class="text-muted">One way</p>
                                <ul class="list-unstyled small">
                                    <li>✓ 20-minute scenic journey</li>
                                    <li>✓ Indoor & outdoor seating</li>
                                    <li>✓ Restroom facilities</li>
                                    <li>✓ Light refreshments available</li>
                                </ul>
                                <div class="mt-3">
                                    @auth
                                        @php
                                            $hasActiveBooking = \App\Models\HotelBooking::where('user_id', auth()->id())
                                                ->where('status', 'confirmed')
                                                ->exists();
                                        @endphp
                                        @if($hasActiveBooking)
                                            <button class="btn btn-primary">Book Standard Ticket</button>
                                        @else
                                            <button class="btn btn-secondary" disabled>Requires Hotel Booking</button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary">Login to Book</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6>⭐ Premium Ferry Ticket</h6>
                            </div>
                            <div class="card-body">
                                <h4 class="text-warning">$25.00</h4>
                                <p class="text-muted">One way</p>
                                <ul class="list-unstyled small">
                                    <li>✓ Priority boarding</li>
                                    <li>✓ Premium seating area</li>
                                    <li>✓ Complimentary drinks</li>
                                    <li>✓ Ocean deck access</li>
                                    <li>✓ Welcome snack</li>
                                </ul>
                                <div class="mt-3">
                                    @auth
                                        @php
                                            $hasActiveBooking = \App\Models\HotelBooking::where('user_id', auth()->id())
                                                ->where('status', 'confirmed')
                                                ->exists();
                                        @endphp
                                        @if($hasActiveBooking)
                                            <button class="btn btn-warning">Book Premium Ticket</button>
                                        @else
                                            <button class="btn btn-secondary" disabled>Requires Hotel Booking</button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-warning">Login to Book</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6>📋 Booking Information</h6>
                    </div>
                    <div class="card-body">
                        @guest
                            <div class="alert alert-info">
                                <h6>How to Book Ferry:</h6>
                                <ol class="small mb-0">
                                    <li><a href="{{ route('register') }}">Create account</a> or <a href="{{ route('login') }}">login</a></li>
                                    <li><a href="{{ route('hotels.index') }}">Book a hotel</a> on Paradise Island</li>
                                    <li>Return here to purchase ferry tickets</li>
                                </ol>
                            </div>
                        @else
                            @php
                                $hasActiveBooking = \App\Models\HotelBooking::where('user_id', auth()->id())
                                    ->where('status', 'confirmed')
                                    ->exists();
                            @endphp
                            @if($hasActiveBooking)
                                <div class="alert alert-success">
                                    <h6>✅ Ready to Book!</h6>
                                    <p class="small mb-0">Hotel booking verified. Choose your ferry ticket above.</p>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <h6>Hotel Booking Required</h6>
                                    <p class="small mb-2">You must have a hotel booking to purchase ferry tickets.</p>
                                    <a href="{{ route('hotels.index') }}" class="btn btn-sm btn-warning">Book Hotel First</a>
                                </div>
                            @endif
                        @endguest
                        
                        <hr>
                        <h6>⚡ Quick Facts:</h6>
                        <ul class="list-unstyled small">
                            <li><strong>Journey time:</strong> 20 minutes</li>
                            <li><strong>Capacity:</strong> 200 passengers</li>
                            <li><strong>Frequency:</strong> Every 2 hours</li>
                            <li><strong>Weather:</strong> All-weather service</li>
                        </ul>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6>🎢 Why Take the Ferry?</h6>
                    </div>
                    <div class="card-body">
                        <p class="small">The ferry is your gateway to Adventure Paradise Theme Park! Experience breathtaking ocean views and spot dolphins during your journey.</p>
                        <a href="{{ route('theme-parks.index') }}" class="btn btn-sm btn-outline-primary">View Theme Park</a>
                    </div>
                </div>
            </div>
        </div>
        
        @guest
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Ready to Set Sail?</h5>
                            <p>Begin your Paradise Island adventure!</p>
                            <a href="{{ route('register') }}" class="btn btn-primary me-2">Create Account</a>
                            <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary">Browse Hotels</a>
                        </div>
                    </div>
                </div>
            </div>
        @endguest
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
