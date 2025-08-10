<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $themePark->name }} - Paradise Island</title>
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
                @else
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-4">
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

        <!-- Theme Park Information -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    @if($themePark->image_url)
                        <img src="{{ $themePark->image_url }}" class="card-img-top" alt="{{ $themePark->name }}" style="height: 300px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h2 class="card-title">{{ $themePark->name }}</h2>
                        <p class="card-text">{{ $themePark->description }}</p>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p><strong>📍 Location:</strong> {{ $themePark->location }}</p>
                                <p><strong>🎫 Entry Price:</strong> ${{ number_format($themePark->entry_price, 2) }}/person</p>
                                <p><strong>👥 Capacity:</strong> {{ number_format($themePark->capacity) }} visitors</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>🕘 Opening Hours:</strong> {{ $themePark->opening_time }} - {{ $themePark->closing_time }}</p>
                                <p><strong>📅 Operating Days:</strong> 
                                    @if(is_array($themePark->operating_days))
                                        {{ implode(', ', $themePark->operating_days) }}
                                    @else
                                        {{ $themePark->operating_days }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activities Section -->
                @if($themePark->activities->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">🎮 Available Activities</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($themePark->activities as $activity)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $activity->name }}</h6>
                                                <p class="card-text">{{ $activity->description }}</p>
                                                <div class="small text-muted">
                                                    <p class="mb-1"><strong>Type:</strong> {{ ucfirst($activity->type) }}</p>
                                                    <p class="mb-1"><strong>Price:</strong> ${{ number_format($activity->price, 2) }}/person</p>
                                                    <p class="mb-1"><strong>Duration:</strong> {{ $activity->duration_minutes }} minutes</p>
                                                    <p class="mb-1"><strong>Capacity:</strong> {{ $activity->capacity_per_session }} people/session</p>
                                                    @if($activity->min_age || $activity->max_age)
                                                        <p class="mb-1"><strong>Age:</strong> 
                                                            @if($activity->min_age && $activity->max_age)
                                                                {{ $activity->min_age }}-{{ $activity->max_age }} years
                                                            @elseif($activity->min_age)
                                                                {{ $activity->min_age }}+ years
                                                            @else
                                                                Up to {{ $activity->max_age }} years
                                                            @endif
                                                        </p>
                                                    @endif
                                                </div>
                                                @auth
                                                    @if(auth()->user()->hasValidHotelBooking())
                                                        <a href="{{ route('activities.book', $activity) }}" class="btn btn-sm btn-outline-primary">Book Activity</a>
                                                    @else
                                                        <span class="text-muted small">Requires hotel booking</span>
                                                    @endif
                                                @else
                                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">Login to Book</a>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Booking Panel -->
            <div class="col-md-4">
                <div class="card sticky-top">
                    <div class="card-header">
                        <h5 class="mb-0">🎢 Book Park Entry</h5>
                    </div>
                    <div class="card-body">
                        @auth
                            @if(auth()->user()->hasValidHotelBooking())
                                <form action="{{ route('parks.booking.store', $themePark) }}" method="POST">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label for="visit_date" class="form-label">Visit Date</label>
                                        <input type="date" class="form-control" id="visit_date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="visitors" class="form-label">Number of Visitors</label>
                                        <select class="form-select" id="visitors" name="visitors" required>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ old('visitors') == $i ? 'selected' : '' }}>{{ $i }} Visitor{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    
                                    <!-- Price Calculation -->
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">
                                            <h6>Booking Summary</h6>
                                            <div id="price-calculation">
                                                <p class="mb-1">Entry Price: ${{ number_format($themePark->entry_price, 2) }}/person</p>
                                                <p class="mb-1">Visitors: <span id="selected-visitors">1</span></p>
                                                <hr>
                                                <p class="mb-0"><strong>Total: $<span id="total-price">{{ number_format($themePark->entry_price, 2) }}</span></strong></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success">Book Now</button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <h6>🏨 Hotel Booking Required</h6>
                                    <p class="mb-2">You need an active hotel booking to access theme parks.</p>
                                    <a href="{{ route('hotels.index') }}" class="btn btn-sm btn-primary">Book Hotel First</a>
                                </div>
                            @endif
                        @else
                            <div class="text-center">
                                <p class="text-muted">Please login to book park tickets</p>
                                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('theme-parks.index') }}" class="btn btn-outline-primary">← Back to Theme Parks</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Price calculation
        function updatePrice() {
            const visitors = parseInt(document.getElementById('visitors').value);
            const pricePerPerson = {{ $themePark->entry_price }};
            const totalPrice = visitors * pricePerPerson;
            
            document.getElementById('selected-visitors').textContent = visitors;
            document.getElementById('total-price').textContent = totalPrice.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        
        // Update price when visitors change
        document.getElementById('visitors').addEventListener('change', updatePrice);
        
        // Initialize price calculation
        updatePrice();
    </script>
</body>
</html>
