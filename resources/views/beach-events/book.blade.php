<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book {{ $beachEvent->name }} - Paradise Island</title>
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

        <!-- Beach Event Information -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        @php
                            $badgeClass = match($beachEvent->type) {
                                'sports' => 'bg-info',
                                'music' => 'bg-warning',
                                'adventure' => 'bg-success',
                                'cultural' => 'bg-purple',
                                'dining' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} mb-2">{{ ucfirst($beachEvent->type) }}</span>
                        <h5 class="card-title">{{ $beachEvent->name }}</h5>
                        <p class="card-text">{{ $beachEvent->description }}</p>
                        <p><strong>📍 Location:</strong> {{ $beachEvent->location }}</p>
                        <p><strong>🕘 Date & Time:</strong><br>
                            {{ \Carbon\Carbon::parse($beachEvent->start_time)->format('M j, Y') }}<br>
                            {{ \Carbon\Carbon::parse($beachEvent->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($beachEvent->end_time)->format('g:i A') }}
                        </p>
                        <p><strong>💰 Price:</strong> ${{ number_format($beachEvent->price, 2) }} per person</p>
                        <p><strong>👥 Capacity:</strong> {{ $beachEvent->capacity }} {{ $beachEvent->type === 'sports' ? 'participants' : 'guests' }}</p>
                        
                        @if($beachEvent->equipment_included && count(json_decode($beachEvent->equipment_included, true) ?: []) > 0)
                            <p><strong>🎯 Included:</strong></p>
                            <ul class="small">
                                @foreach(json_decode($beachEvent->equipment_included, true) ?: [] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @endif
                        
                        @if($beachEvent->requirements)
                            <div class="alert alert-info p-2">
                                <small><strong>Requirements:</strong> {{ $beachEvent->requirements }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Booking Form -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">🎫 Book Your Spot</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('beach-events.booking.store', $beachEvent) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="participants" class="form-label">Number of {{ $beachEvent->type === 'sports' ? 'Participants' : 'Guests' }}</label>
                                        <select class="form-select" id="participants" name="participants" required>
                                            @for($i = 1; $i <= min(10, $beachEvent->capacity); $i++)
                                                <option value="{{ $i }}" {{ old('participants', $participants) == $i ? 'selected' : '' }}>
                                                    {{ $i }} {{ $i > 1 ? ($beachEvent->type === 'sports' ? 'Participants' : 'Guests') : ($beachEvent->type === 'sports' ? 'Participant' : 'Guest') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Event Date</label>
                                        <input type="text" class="form-control" 
                                               value="{{ \Carbon\Carbon::parse($beachEvent->start_time)->format('M j, Y') }}" 
                                               readonly disabled>
                                        <small class="text-muted">This event is scheduled for a specific date</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_email" class="form-label">Contact Email</label>
                                        <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                               value="{{ old('contact_email', auth()->user()->email) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_phone" class="form-label">Contact Phone</label>
                                        <input type="tel" class="form-control" id="contact_phone" name="contact_phone" 
                                               value="{{ old('contact_phone') }}" placeholder="+1 (555) 123-4567" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="special_requirements" class="form-label">Special Requirements or Notes (Optional)</label>
                                <textarea class="form-control" id="special_requirements" name="special_requirements" 
                                          rows="3" placeholder="Any dietary restrictions, accessibility needs, or special requests...">{{ old('special_requirements') }}</textarea>
                            </div>
                            
                            <!-- Price Calculation Preview -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6>Booking Summary</h6>
                                    <div id="price-calculation">
                                        <p class="mb-1">Event: {{ $beachEvent->name }}</p>
                                        <p class="mb-1">Price per person: ${{ number_format($beachEvent->price, 2) }}</p>
                                        <p class="mb-1">{{ $beachEvent->type === 'sports' ? 'Participants' : 'Guests' }}: <span id="selected-participants">{{ $participants }}</span></p>
                                        <hr>
                                        <p class="mb-0"><strong>Total: $<span id="total-price">{{ number_format($beachEvent->price * $participants, 2) }}</span></strong></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('beach-events.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Confirm Booking</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('beach-events.index') }}" class="btn btn-outline-primary">← Back to Beach Events</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Price calculation
        function updatePrice() {
            const participants = parseInt(document.getElementById('participants').value);
            const pricePerPerson = {{ $beachEvent->price }};
            const totalPrice = participants * pricePerPerson;
            
            document.getElementById('selected-participants').textContent = participants;
            document.getElementById('total-price').textContent = totalPrice.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        
        // Update price when participants change
        document.getElementById('participants').addEventListener('change', updatePrice);
        
        // Initialize price calculation
        updatePrice();
    </script>
</body>
</html>
