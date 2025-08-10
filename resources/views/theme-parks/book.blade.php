<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book {{ $themePark->name }} - Paradise Island</title>
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

        <!-- Theme Park Information -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    @if($themePark->image_url)
                        <img src="{{ $themePark->image_url }}" class="card-img-top" alt="{{ $themePark->name }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $themePark->name }}</h5>
                        <p class="card-text">{{ $themePark->description }}</p>
                        <p><strong>Location:</strong> {{ $themePark->location }}</p>
                        <p><strong>Entry Price:</strong> ${{ number_format($themePark->entry_price, 2) }}/person</p>
                        <p><strong>Opening Hours:</strong> {{ $themePark->opening_time }} - {{ $themePark->closing_time }}</p>
                        <p><strong>Capacity:</strong> {{ number_format($themePark->capacity) }} visitors</p>
                    </div>
                </div>
            </div>
            
            <!-- Booking Form -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">🎢 Book Theme Park Entry</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('parks.booking.store', $themePark) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="visit_date" class="form-label">Visit Date</label>
                                        <input type="date" class="form-control" id="visit_date" name="visit_date" value="{{ old('visit_date', $visit_date) }}" min="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="visitors" class="form-label">Number of Visitors</label>
                                        <select class="form-select" id="visitors" name="visitors" required>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ old('visitors', $visitors) == $i ? 'selected' : '' }}>{{ $i }} Visitor{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Price Calculation Preview -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6>Booking Summary</h6>
                                    <div id="price-calculation">
                                        <p class="mb-1">Entry Price: ${{ number_format($themePark->entry_price, 2) }}/person</p>
                                        <p class="mb-1">Visitors: <span id="selected-visitors">{{ $visitors }}</span></p>
                                        <hr>
                                        <p class="mb-0"><strong>Total: $<span id="total-price">{{ number_format($themePark->entry_price * $visitors, 2) }}</span></strong></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('theme-parks.show', $themePark) }}" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-success">Confirm Booking</button>
                            </div>
                        </form>
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
