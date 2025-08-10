<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book {{ $hotel->name }} - Paradise Island</title>
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

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Hotel Information -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $hotel->name }}</h5>
                        <p class="card-text">{{ $hotel->description }}</p>
                        <p><strong>Location:</strong> {{ $hotel->location }}</p>
                        <p><strong>Price:</strong> ${{ number_format($hotel->price_per_night, 2) }}/night</p>
                        <p><strong>Available Rooms:</strong> {{ $hotel->available_rooms }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">{{ ucfirst($hotel->category) }}</span>
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $hotel->rating)
                                        <span class="text-warning">★</span>
                                    @else
                                        <span class="text-muted">☆</span>
                                    @endif
                                @endfor
                                <small class="text-muted">({{ $hotel->rating }}/5)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Booking Form -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">🏨 Book Your Stay</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('hotels.booking.store', $hotel) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="check_in" class="form-label">Check-in Date</label>
                                        <input type="date" class="form-control" id="check_in" name="check_in" value="{{ old('check_in', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="check_out" class="form-label">Check-out Date</label>
                                        <input type="date" class="form-control" id="check_out" name="check_out" value="{{ old('check_out', date('Y-m-d', strtotime('+1 day'))) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="rooms" class="form-label">Rooms</label>
                                        <select class="form-select" id="rooms" name="rooms" required>
                                            @for($i = 1; $i <= min(5, $hotel->available_rooms); $i++)
                                                <option value="{{ $i }}" {{ old('rooms') == $i ? 'selected' : '' }}>{{ $i }} Room{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="guests" class="form-label">Guests</label>
                                        <select class="form-select" id="guests" name="guests" required>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ old('guests') == $i ? 'selected' : '' }}>{{ $i }} Guest{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="room_type" class="form-label">Room Type</label>
                                <select class="form-select" id="room_type" name="room_type" required>
                                    <option value="standard" {{ old('room_type') == 'standard' ? 'selected' : '' }}>Standard Room</option>
                                    <option value="deluxe" {{ old('room_type') == 'deluxe' ? 'selected' : '' }}>Deluxe Room</option>
                                    <option value="suite" {{ old('room_type') == 'suite' ? 'selected' : '' }}>Suite</option>
                                    <option value="presidential" {{ old('room_type') == 'presidential' ? 'selected' : '' }}>Presidential Suite</option>
                                </select>
                            </div>
                            
                            <!-- Price Calculation Preview -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6>Booking Summary</h6>
                                    <div id="price-calculation">
                                        <p class="mb-1">Base Rate: ${{ number_format($hotel->price_per_night, 2) }}/night</p>
                                        <p class="mb-1">Nights: <span id="nights">1</span></p>
                                        <p class="mb-1">Rooms: <span id="selected-rooms">1</span></p>
                                        <hr>
                                        <p class="mb-0"><strong>Total: $<span id="total-price">{{ number_format($hotel->price_per_night, 2) }}</span></strong></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('hotels.show', $hotel) }}" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Confirm Booking</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary">← Back to Hotels</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Price calculation
        function updatePrice() {
            const checkIn = new Date(document.getElementById('check_in').value);
            const checkOut = new Date(document.getElementById('check_out').value);
            const rooms = parseInt(document.getElementById('rooms').value);
            const pricePerNight = {{ $hotel->price_per_night }};
            
            if (checkIn && checkOut && checkOut > checkIn) {
                const timeDiff = checkOut.getTime() - checkIn.getTime();
                const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                const totalPrice = nights * pricePerNight * rooms;
                
                document.getElementById('nights').textContent = nights;
                document.getElementById('selected-rooms').textContent = rooms;
                document.getElementById('total-price').textContent = totalPrice.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        }
        
        // Update price when dates or rooms change
        document.getElementById('check_in').addEventListener('change', updatePrice);
        document.getElementById('check_out').addEventListener('change', updatePrice);
        document.getElementById('rooms').addEventListener('change', updatePrice);
        
        // Ensure check-out is after check-in
        document.getElementById('check_in').addEventListener('change', function() {
            const checkIn = new Date(this.value);
            const checkOut = document.getElementById('check_out');
            const minCheckOut = new Date(checkIn.getTime() + 24 * 60 * 60 * 1000);
            checkOut.min = minCheckOut.toISOString().split('T')[0];
            
            if (new Date(checkOut.value) <= checkIn) {
                checkOut.value = minCheckOut.toISOString().split('T')[0];
            }
            updatePrice();
        });
        
        // Initialize price calculation
        updatePrice();
    </script>
</body>
</html>
