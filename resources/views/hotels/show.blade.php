<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hotel->name }} - Paradise Island</title>
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
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hotels.index') }}">Hotels</a></li>
                        <li class="breadcrumb-item active">{{ $hotel->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h2 mb-0">{{ $hotel->name }}</h1>
                        <div class="mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $hotel->star_rating)
                                    <span class="text-warning">★</span>
                                @else
                                    <span class="text-muted">☆</span>
                                @endif
                            @endfor
                            <span class="ms-2 text-muted">({{ $hotel->star_rating }} Star Hotel)</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Hotel Information</h5>
                                <p><strong>Location:</strong> {{ $hotel->location }}</p>
                                <p><strong>Total Rooms:</strong> {{ $hotel->total_rooms }}</p>
                                <p><strong>Available Rooms:</strong> {{ $hotel->available_rooms }}</p>
                                <p><strong>Check-in:</strong> {{ $hotel->check_in_time }}</p>
                                <p><strong>Check-out:</strong> {{ $hotel->check_out_time }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Contact Details</h5>
                                <p><strong>Phone:</strong> {{ $hotel->phone }}</p>
                                <p><strong>Email:</strong> {{ $hotel->email }}</p>
                                @if($hotel->website)
                                    <p><strong>Website:</strong> <a href="{{ $hotel->website }}" target="_blank">{{ $hotel->website }}</a></p>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <h5>Description</h5>
                        <p>{{ $hotel->description }}</p>

                        @php
                            $amenities = json_decode($hotel->amenities, true) ?: [];
                        @endphp
                        @if($amenities && count($amenities) > 0)
                            <hr>
                            <h5>Amenities</h5>
                            <div class="row">
                                @foreach($amenities as $amenity)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="text-success me-2">✓</span>
                                            <span>{{ $amenity }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($hotel->images && count($hotel->images) > 0)
                            <hr>
                            <h5>Gallery</h5>
                            <div class="row">
                                @foreach($hotel->images as $image)
                                    <div class="col-md-4 mb-3">
                                        <img src="{{ $image }}" class="img-fluid rounded" alt="Hotel Image">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Book This Hotel</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <span class="h3 text-primary">${{ number_format($hotel->price_per_night, 2) }}</span>
                            <span class="text-muted">/ night</span>
                        </div>

                        @if($hotel->available_rooms > 0)
                            @auth
                                @php
                                    $hasActiveBooking = Auth::user()->hotelBookings()
                                        ->where('hotel_id', $hotel->id)
                                        ->whereIn('status', ['pending', 'confirmed'])
                                        ->exists();
                                @endphp

                                @if($hasActiveBooking)
                                    <div class="alert alert-success">
                                        <h6>✅ Already Booked</h6>
                                        <p class="mb-2">You have an active booking at this hotel.</p>
                                        <a href="{{ route('hotel-bookings.index') }}" class="btn btn-sm btn-outline-primary">View My Bookings</a>
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('hotels.book', $hotel) }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="check_in" class="form-label">Check-in Date</label>
                                            <input type="date" class="form-control" id="check_in" name="check_in" 
                                                   min="{{ date('Y-m-d') }}" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="check_out" class="form-label">Check-out Date</label>
                                            <input type="date" class="form-control" id="check_out" name="check_out" 
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="guests" class="form-label">Number of Guests</label>
                                            <select class="form-select" id="guests" name="guests" required>
                                                <option value="">Select guests</option>
                                                @for($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="rooms" class="form-label">Number of Rooms</label>
                                            <select class="form-select" id="rooms" name="rooms" required>
                                                <option value="">Select rooms</option>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Room' : 'Rooms' }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="room_type" class="form-label">Room Type</label>
                                            <select class="form-select" id="room_type" name="room_type" required>
                                                <option value="">Select room type</option>
                                                <option value="standard">Standard Room</option>
                                                <option value="deluxe">Deluxe Room</option>
                                                <option value="suite">Suite</option>
                                                <option value="presidential">Presidential Suite</option>
                                            </select>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Book Now</button>
                                        </div>
                                    </form>
                                @endif
                            @else
                                <div class="alert alert-info">
                                    <p class="mb-0">Please <a href="{{ route('login') }}">login</a> to book this hotel.</p>
                                </div>
                            @endauth
                        @else
                            <div class="alert alert-warning">
                                <h6>Fully Booked</h6>
                                <p class="mb-0">Sorry, this hotel is currently fully booked.</p>
                            </div>
                        @endif

                        <hr>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                ⭐ Book a hotel to unlock ferry services and theme park access!
                            </small>
                        </div>
                    </div>
                </div>

                @if($hotel->location_details)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6>Location Details</h6>
                        </div>
                        <div class="card-body">
                            <p class="small">{{ $hotel->location_details }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="text-center">
                    <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary">← Back to All Hotels</a>
                    <a href="{{ route('map') }}" class="btn btn-outline-info ms-2">🗺️ View on Map</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-update checkout date when checkin date changes
        document.getElementById('check_in').addEventListener('change', function() {
            const checkIn = new Date(this.value);
            const checkOut = new Date(checkIn);
            checkOut.setDate(checkOut.getDate() + 1);
            
            const checkOutInput = document.getElementById('check_out');
            checkOutInput.min = checkOut.toISOString().split('T')[0];
            
            if (checkOutInput.value && new Date(checkOutInput.value) <= checkIn) {
                checkOutInput.value = checkOut.toISOString().split('T')[0];
            }
        });
    </script>
</body>
</html>
