<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ferry->name }} - Ferry Details - Paradise Island</title>
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

    <!-- Ferry Details Section -->
    <div class="container my-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ferries.index') }}">Ferries</a></li>
                <li class="breadcrumb-item active">{{ $ferry->name }}</li>
            </ol>
        </nav>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <!-- Ferry Information -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h3 class="mb-0">⛴️ {{ $ferry->name }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="lead">{{ $ferry->description ?? 'Premium ferry service with modern amenities and professional crew.' }}</p>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>🗺️ Route Information</h5>
                                <div class="border p-3 rounded bg-light">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span><strong>From:</strong> {{ $ferry->departure_location }}</span>
                                        <span class="text-muted">→</span>
                                        <span><strong>To:</strong> {{ $ferry->arrival_location }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span><strong>Departure:</strong> {{ $ferry->departure_time }}</span>
                                        <span><strong>Arrival:</strong> {{ $ferry->arrival_time }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>📋 Ferry Details</h5>
                                <div class="border p-3 rounded bg-light">
                                    <div class="mb-2">
                                        <strong>Capacity:</strong> {{ $ferry->capacity }} passengers
                                    </div>
                                    <div class="mb-2">
                                        <strong>Operating Days:</strong> 
                                        @if($ferry->operating_days)
                                            {{ implode(', ', json_decode($ferry->operating_days, true)) }}
                                        @else
                                            Daily
                                        @endif
                                    </div>
                                    <div>
                                        <strong>Status:</strong> 
                                        @if($ferry->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Amenities and Features -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5>✨ Ferry Amenities</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li>✅ Air-conditioned cabin</li>
                                            <li>✅ Comfortable seating</li>
                                            <li>✅ Panoramic windows</li>
                                            <li>✅ Safety equipment</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li>✅ Onboard restrooms</li>
                                            <li>✅ Luggage storage</li>
                                            <li>✅ Professional crew</li>
                                            <li>✅ Weather protection</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Important Information -->
                        <div class="alert alert-info">
                            <h6>📢 Important Information</h6>
                            <ul class="mb-0">
                                <li>Please arrive at least 15 minutes before departure</li>
                                <li>Valid ID required for all passengers</li>
                                <li>Weather conditions may affect ferry schedule</li>
                                <li>Tickets are non-refundable but can be rescheduled</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Panel -->
            <div class="col-md-4">
                <div class="card shadow sticky-top">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">🎫 Book Your Tickets</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="display-6 text-success fw-bold">${{ $ferry->price }}</span>
                            <div class="text-muted">per person</div>
                        </div>

                        @auth
                            @if($hasValidHotelBooking)
                                <form action="{{ route('ferries.book', $ferry) }}" method="GET">
                                    <div class="mb-3">
                                        <label for="passengers" class="form-label">Number of Passengers</label>
                                        <select class="form-select" name="passengers" id="passengers" required>
                                            <option value="">Select passengers</option>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'passenger' : 'passengers' }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="travel_date" class="form-label">Travel Date</label>
                                        <input type="date" class="form-control" name="travel_date" id="travel_date" 
                                               min="{{ date('Y-m-d') }}" required>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            Continue to Booking
                                        </button>
                                    </div>
                                </form>

                                <div class="mt-3 text-center">
                                    <small class="text-muted">You'll review details before payment</small>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <h6>⚠️ Hotel Booking Required</h6>
                                    <p class="small mb-2">You must have an active hotel booking before purchasing ferry tickets.</p>
                                    <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary btn-sm">
                                        Book Hotel First
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <h6>Please Login</h6>
                                <p class="small mb-2">Login to book ferry tickets</p>
                                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card shadow mt-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">📞 Need Help?</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <p class="small mb-2"><strong>Ferry Booking Hotline</strong></p>
                            <p class="mb-2">📞 +1-800-FERRY</p>
                            <p class="small text-muted">Available 24/7</p>
                            <hr>
                            <p class="small">✉️ ferries@paradise-island.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Ferries -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('ferries.index') }}" class="btn btn-outline-primary">
                    ← Back to All Ferries
                </a>
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
