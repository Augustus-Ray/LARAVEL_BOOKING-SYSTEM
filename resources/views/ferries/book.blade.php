<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book {{ $ferry->name }} - Paradise Island</title>
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
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Booking Form -->
    <div class="container my-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ferries.index') }}">Ferries</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ferries.show', $ferry) }}">{{ $ferry->name }}</a></li>
                <li class="breadcrumb-item active">Book Tickets</li>
            </ol>
        </nav>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <!-- Booking Form -->
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">🎫 Book Ferry Tickets</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ferries.booking.store', $ferry) }}" method="POST" id="bookingForm">
                            @csrf
                            
                            <!-- Travel Details -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="passengers" class="form-label">Number of Passengers</label>
                                    <select class="form-select @error('passengers') is-invalid @enderror" 
                                            name="passengers" id="passengers" required onchange="updatePassengerForms()">
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ $passengers == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'passenger' : 'passengers' }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('passengers')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="travel_date" class="form-label">Travel Date</label>
                                    <input type="date" class="form-control @error('travel_date') is-invalid @enderror" 
                                           name="travel_date" id="travel_date" value="{{ $travel_date }}" 
                                           min="{{ date('Y-m-d') }}" required onchange="updatePrice()">
                                    @error('travel_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Passenger Information -->
                            <div class="mb-4">
                                <h5>👥 Passenger Information</h5>
                                <div id="passengerForms">
                                    <!-- Passenger forms will be generated here -->
                                </div>
                            </div>

                            <!-- Terms and Submit -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" target="_blank">Terms and Conditions</a> and <a href="#" target="_blank">Cancellation Policy</a>
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    Confirm Booking 
                                </button>
                                <a href="{{ route('ferries.show', $ferry) }}" class="btn btn-outline-secondary">
                                    Back to Ferry Details
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Booking Summary -->
            <div class="col-md-4">
                <div class="card shadow sticky-top">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">📋 Booking Summary</h5>
                    </div>
                    <div class="card-body">
                        <!-- Ferry Details -->
                        <div class="mb-3">
                            <h6>⛴️ {{ $ferry->name }}</h6>
                            <small class="text-muted">{{ $ferry->departure_location }} → {{ $ferry->arrival_location }}</small>
                        </div>

                        <hr>

                        <!-- Trip Details -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Travel Date:</span>
                                <strong id="summaryDate">{{ $travel_date }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Departure:</span>
                                <strong>{{ $ferry->departure_time }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Arrival:</span>
                                <strong>{{ $ferry->arrival_time }}</strong>
                            </div>
                        </div>

                        <hr>

                        <!-- Pricing -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Passengers:</span>
                                <strong id="summaryPassengers">{{ $passengers }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Price per ticket:</span>
                                <span>${{ $ferry->price }}</span>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="h5">Total:</span>
                            <span class="h5 text-success" id="totalPrice">${{ $ferry->price * $passengers }}</span>
                        </div>

                        <!-- Payment Info -->
                        <div class="alert alert-info">
                            <small>
                                <strong>Payment:</strong> Secure payment processing<br>
                                <strong>Confirmation:</strong> Instant email confirmation<br>
                                <strong>Cancellation:</strong> Free up to 24 hours before
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 Paradise Island Resort. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ferryPrice = {{ $ferry->price }};
        
        function updatePassengerForms() {
            const passengers = document.getElementById('passengers').value;
            const container = document.getElementById('passengerForms');
            const summaryPassengers = document.getElementById('summaryPassengers');
            
            container.innerHTML = '';
            
            for (let i = 1; i <= passengers; i++) {
                const passengerForm = `
                    <div class="mb-3 border p-3 rounded">
                        <h6>Passenger ${i}</h6>
                        <input type="text" class="form-control" name="passenger_names[]" 
                               placeholder="Full Name" required>
                    </div>
                `;
                container.innerHTML += passengerForm;
            }
            
            summaryPassengers.textContent = passengers;
            updatePrice();
        }
        
        function updatePrice() {
            const passengers = document.getElementById('passengers').value;
            const totalPrice = ferryPrice * passengers;
            document.getElementById('totalPrice').textContent = '$' + totalPrice;
        }
        
        function updateDate() {
            const travelDate = document.getElementById('travel_date').value;
            document.getElementById('summaryDate').textContent = travelDate;
        }
        
        // Initialize passenger forms
        updatePassengerForms();
        
        // Update date on change
        document.getElementById('travel_date').addEventListener('change', updateDate);
    </script>
</body>
</html>
