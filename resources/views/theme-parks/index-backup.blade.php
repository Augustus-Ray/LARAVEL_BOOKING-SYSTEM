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
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">🎢 Adventure Land</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Experience the ultimate thrill with our signature roller coasters and adventure rides.</p>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Main Attractions:</strong><br>
                                        <small class="text-muted">🚀 Space Exploration Roller Coaster</small><br>
                                        <small class="text-muted">🌊 Tsunami Wave Rider</small>
                                    </div>
                                    <div class="col-6">
                                        <strong>Age Requirements:</strong><br>
                                        <small class="text-muted">All ages welcome</small><br>
                                        <small class="text-muted">Height restrictions apply</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 text-success mb-0">$45.00</span>
                                    <small class="text-muted">per person</small>
                                </div>
                                <a href="#" class="btn btn-success">Book Now</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">🌊 Aqua World</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Dive into underwater adventures with our marine-themed attractions and water rides.</p>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Main Attractions:</strong><br>
                                        <small class="text-muted">🐠 Glow-in-the-Dark Coral Ride</small><br>
                                        <small class="text-muted">🦈 Shark Tank Adventure</small>
                                    </div>
                                    <div class="col-6">
                                        <strong>Features:</strong><br>
                                        <small class="text-muted">Interactive experiences</small><br>
                                        <small class="text-muted">Educational tours</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 text-info mb-0">$38.00</span>
                                    <small class="text-muted">per person</small>
                                </div>
                                <a href="#" class="btn btn-info">Book Now</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="card-title mb-0">🏰 Fantasy Kingdom</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Enter a magical world filled with fairy tales, castles, and enchanted adventures.</p>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Main Attractions:</strong><br>
                                        <small class="text-muted">🏰 Enchanted Castle Tour</small><br>
                                        <small class="text-muted">🧚 Fairy Tale Adventure</small>
                                    </div>
                                    <div class="col-6">
                                        <strong>Perfect For:</strong><br>
                                        <small class="text-muted">Families with kids</small><br>
                                        <small class="text-muted">Photo opportunities</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 text-warning mb-0">$32.00</span>
                                    <small class="text-muted">per person</small>
                                </div>
                                <a href="#" class="btn btn-warning">Book Now</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow">
                            <div class="card-header bg-danger text-white">
                                <h5 class="card-title mb-0">🔥 Extreme Zone</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">For adrenaline junkies seeking the most intense and extreme ride experiences.</p>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Main Attractions:</strong><br>
                                        <small class="text-muted">⚡ Lightning Strike Drop</small><br>
                                        <small class="text-muted">🌪️ Tornado Spinner</small>
                                    </div>
                                    <div class="col-6">
                                        <strong>Requirements:</strong><br>
                                        <small class="text-muted">Ages 16+</small><br>
                                        <small class="text-muted">Health restrictions apply</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 text-danger mb-0">$55.00</span>
                                    <small class="text-muted">per person</small>
                                </div>
                                <a href="#" class="btn btn-danger">Book Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Individual Activities -->
                <div class="mt-5">
                    <h3 class="mb-4">🎯 Individual Activities</h3>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">🎮 VR Experience Center</h6>
                                    <p class="card-text">Immerse yourself in virtual reality adventures.</p>
                                    <p><strong>Price:</strong> $20.00 | <strong>Duration:</strong> 30 mins</p>
                                    <button class="btn btn-outline-primary btn-sm">Book Activity</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">🎯 Laser Tag Arena</h6>
                                    <p class="card-text">Team-based laser tag battles in themed environments.</p>
                                    <p><strong>Price:</strong> $25.00 | <strong>Duration:</strong> 45 mins</p>
                                    <button class="btn btn-outline-primary btn-sm">Book Activity</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">🧗 Rock Climbing Wall</h6>
                                    <p class="card-text">Challenge yourself on our 20-meter climbing wall.</p>
                                    <p><strong>Price:</strong> $30.00 | <strong>Duration:</strong> 60 mins</p>
                                    <button class="btn btn-outline-primary btn-sm">Book Activity</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">🏄 Water Sports Arena</h6>
                                    <p class="card-text">Jet skiing, paddleboarding, kayaking, and snorkeling.</p>
                                    <p><strong>Price:</strong> $35.00 | <strong>Duration:</strong> 60 mins</p>
                                    <button class="btn btn-outline-primary btn-sm">Book Activity</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <h5>⚠️ Hotel Booking Required</h5>
                    <p>You must have an active hotel booking before you can purchase theme park tickets.</p>
                    <a href="{{ route('hotels.index') }}" class="btn btn-primary">Book Hotel First</a>
                </div>
            @endif
        @else
            <div class="alert alert-info">
                <h5>Please Login</h5>
                <p><a href="{{ route('login') }}" class="alert-link">Login</a> to view and book theme park tickets.</p>
            </div>
        @endauth
    </div>

    <!-- Park Information -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">🗺️ Park Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>🕒 Operating Hours</h6>
                                    <ul class="list-unstyled">
                                        <li>✅ Monday - Friday: 10:00 AM - 8:00 PM</li>
                                        <li>✅ Saturday - Sunday: 9:00 AM - 10:00 PM</li>
                                        <li>✅ Holidays: 9:00 AM - 11:00 PM</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>🎫 Ticket Information</h6>
                                    <ul class="list-unstyled">
                                        <li>✅ All-day access included</li>
                                        <li>✅ Free parking available</li>
                                        <li>✅ Re-entry allowed same day</li>
                                        <li>✅ Group discounts available</li>
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
                                <li>🌦️ <strong>Weather dependent rides</strong></li>
                                <li>📏 <strong>Height restrictions apply</strong></li>
                                <li>♿ <strong>Wheelchair accessible</strong></li>
                                <li>🍔 <strong>Food courts available</strong></li>
                            </ul>
                        </div>
                    </div>
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
                    <p class="small">Theme Parks: +1-800-PARKS<br>Email: parks@paradise-island.com</p>
                </div>
                <div class="col-md-4">
                    <h6>🕒 Operating Hours</h6>
                    <p class="small">Daily: 9:00 AM - 10:00 PM<br>Extended hours on weekends</p>
                </div>
            </div>
            <hr>
            <p class="mb-0">&copy; 2024 Paradise Island Resort. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
