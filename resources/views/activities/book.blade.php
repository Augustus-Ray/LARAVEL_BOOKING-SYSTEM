<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book {{ $parkActivity->name }} - Paradise Island</title>
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

        <!-- Activity Information -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $parkActivity->name }}</h5>
                        <p class="card-text">{{ $parkActivity->description }}</p>
                        <p><strong>Theme Park:</strong> {{ $parkActivity->themePark->name }}</p>
                        <p><strong>Type:</strong> {{ ucfirst($parkActivity->type) }}</p>
                        <p><strong>Price:</strong> ${{ number_format($parkActivity->price, 2) }}/person</p>
                        <p><strong>Duration:</strong> {{ $parkActivity->duration_minutes }} minutes</p>
                        <p><strong>Capacity:</strong> {{ $parkActivity->capacity_per_session }} people/session</p>
                        @if($parkActivity->min_age || $parkActivity->max_age)
                            <p><strong>Age Requirement:</strong> 
                                @if($parkActivity->min_age && $parkActivity->max_age)
                                    {{ $parkActivity->min_age }}-{{ $parkActivity->max_age }} years
                                @elseif($parkActivity->min_age)
                                    {{ $parkActivity->min_age }}+ years
                                @else
                                    Up to {{ $parkActivity->max_age }} years
                                @endif
                            </p>
                        @endif
                        <p><strong>Operating Hours:</strong> {{ $parkActivity->start_time }} - {{ $parkActivity->end_time }}</p>
                    </div>
                </div>

                <!-- Park Ticket Info -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">🎫 Your Park Ticket</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Reference:</strong> {{ $parkTicket->ticket_reference }}</p>
                        <p><strong>Visit Date:</strong> {{ $parkTicket->visit_date->format('M d, Y') }}</p>
                        <p><strong>Visitors:</strong> {{ $parkTicket->visitors }}</p>
                        <span class="badge bg-success">Valid for Activities</span>
                    </div>
                </div>
            </div>
            
            <!-- Booking Form -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">🎮 Book Activity</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('activities.booking.store', $parkActivity) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="scheduled_time" class="form-label">Scheduled Date & Time</label>
                                        <input type="datetime-local" class="form-control" id="scheduled_time" name="scheduled_time" 
                                               value="{{ old('scheduled_time', $scheduled_time) }}" 
                                               min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}" 
                                               max="{{ $parkTicket->visit_date->format('Y-m-d') }}T{{ $parkActivity->end_time }}" 
                                               required>
                                        <div class="form-text">Must be at least 30 minutes from now and within your park visit date.</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="participants" class="form-label">Number of Participants</label>
                                        <select class="form-select" id="participants" name="participants" required>
                                            @for($i = 1; $i <= min($parkActivity->capacity_per_session, $parkTicket->visitors); $i++)
                                                <option value="{{ $i }}" {{ old('participants', $participants) == $i ? 'selected' : '' }}>{{ $i }} Person{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>
                                        <div class="form-text">Maximum based on your park ticket ({{ $parkTicket->visitors }} visitors) and activity capacity.</div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($parkActivity->requirements)
                                <div class="mb-3">
                                    <label class="form-label">Requirements</label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <ul class="mb-0">
                                                @foreach(json_decode($parkActivity->requirements, true) ?: [] as $requirement)
                                                    <li>{{ $requirement }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Price Calculation Preview -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6>Booking Summary</h6>
                                    <div id="price-calculation">
                                        <p class="mb-1">Activity: {{ $parkActivity->name }}</p>
                                        <p class="mb-1">Duration: {{ $parkActivity->duration_minutes }} minutes</p>
                                        <p class="mb-1">Price: ${{ number_format($parkActivity->price, 2) }}/person</p>
                                        <p class="mb-1">Participants: <span id="selected-participants">{{ $participants }}</span></p>
                                        <hr>
                                        <p class="mb-0"><strong>Total: $<span id="total-price">{{ number_format($parkActivity->price * $participants, 2) }}</span></strong></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('theme-parks.show', $parkActivity->themePark) }}" class="btn btn-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-success">Confirm Booking</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('theme-parks.show', $parkActivity->themePark) }}" class="btn btn-outline-primary">← Back to {{ $parkActivity->themePark->name }}</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Price calculation
        function updatePrice() {
            const participants = parseInt(document.getElementById('participants').value);
            const pricePerPerson = {{ $parkActivity->price }};
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

        // Set minimum time for scheduling based on park opening hours and current time
        document.addEventListener('DOMContentLoaded', function() {
            const scheduledTimeInput = document.getElementById('scheduled_time');
            const parkDate = '{{ $parkTicket->visit_date->format('Y-m-d') }}';
            const startTime = '{{ $parkActivity->start_time }}';
            const endTime = '{{ $parkActivity->end_time }}';
            
            // Set min time to be the later of: 30 minutes from now OR park opening time on visit date
            const now = new Date();
            const minTime = new Date(now.getTime() + 30 * 60000); // 30 minutes from now
            const parkOpenTime = new Date(parkDate + 'T' + startTime);
            
            const actualMinTime = minTime > parkOpenTime ? minTime : parkOpenTime;
            scheduledTimeInput.min = actualMinTime.toISOString().slice(0, 16);
        });
    </script>
</body>
</html>
