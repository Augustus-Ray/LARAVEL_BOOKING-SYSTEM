<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .booking-card {
            border-left: 4px solid #007bff;
            transition: transform 0.2s;
        }
        .booking-card:hover {
            transform: translateY(-2px);
        }
        .status-pending { border-left-color: #ffc107; }
        .status-confirmed { border-left-color: #28a745; }
        .status-cancelled { border-left-color: #dc3545; }
        .payment-pending { border-left-color: #fd7e14; }
        .payment-paid { border-left-color: #20c997; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-island-tropical me-2"></i>Paradise Island
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link active" href="{{ route('hotel-bookings.index') }}">My Bookings</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2><i class="fas fa-calendar-check me-2"></i>My Bookings</h2>
                <p class="text-muted">Manage your Paradise Island bookings and payments</p>
            </div>
        </div>

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

        <!-- Total Payment Summary -->
        @php
            $totalUnpaid = 0;
            $unpaidBookings = [];
            
            if(isset($hotelBookings)) {
                foreach($hotelBookings as $booking) {
                    if(($booking->payment_status ?? 'pending') === 'pending' && $booking->status !== 'cancelled') {
                        $totalUnpaid += $booking->total_price;
                        $unpaidBookings[] = ['type' => 'hotel', 'id' => $booking->id, 'amount' => $booking->total_price, 'name' => $booking->hotel->name];
                    }
                }
            }
            
            if(isset($ferryTickets)) {
                foreach($ferryTickets as $ticket) {
                    if(($ticket->payment_status ?? 'pending') === 'pending' && $ticket->status !== 'cancelled') {
                        $totalUnpaid += $ticket->total_price;
                        $unpaidBookings[] = ['type' => 'ferry', 'id' => $ticket->id, 'amount' => $ticket->total_price, 'name' => $ticket->ferry->name];
                    }
                }
            }
            
            if(isset($parkTickets)) {
                foreach($parkTickets as $ticket) {
                    if(($ticket->payment_status ?? 'pending') === 'pending' && $ticket->status !== 'cancelled') {
                        $totalUnpaid += $ticket->total_price;
                        $unpaidBookings[] = ['type' => 'park', 'id' => $ticket->id, 'amount' => $ticket->total_price, 'name' => $ticket->themePark->name];
                    }
                }
            }
            
            if(isset($beachTickets)) {
                foreach($beachTickets as $ticket) {
                    if(($ticket->payment_status ?? 'pending') === 'pending' && $ticket->status !== 'cancelled') {
                        $totalUnpaid += $ticket->total_price;
                        $unpaidBookings[] = ['type' => 'beach', 'id' => $ticket->id, 'amount' => $ticket->total_price, 'name' => $ticket->beachEvent->name];
                    }
                }
            }
        @endphp

        @if($totalUnpaid > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-warning bg-light">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Payment Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6>Outstanding Payments</h6>
                                <p class="mb-2">You have <strong>{{ count($unpaidBookings) }}</strong> unpaid booking{{ count($unpaidBookings) > 1 ? 's' : '' }}</p>
                                <div class="small text-muted">
                                    @foreach($unpaidBookings as $booking)
                                        <span class="badge bg-secondary me-1">{{ $booking['name'] }} - ${{ number_format($booking['amount'], 2) }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <h4 class="text-warning mb-3">Total: ${{ number_format($totalUnpaid, 2) }}</h4>
                                <button class="btn btn-success btn-lg" onclick="processAllPayments({{ json_encode($unpaidBookings) }}, {{ $totalUnpaid }})">
                                    <i class="fas fa-credit-card me-2"></i>Pay All Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Hotel Bookings -->
        @if(isset($hotelBookings) && $hotelBookings->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <h4><i class="fas fa-hotel me-2"></i>Hotel Bookings</h4>
                @foreach($hotelBookings as $booking)
                <div class="card booking-card status-{{ $booking->status }} payment-{{ $booking->payment_status ?? 'pending' }} mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ $booking->hotel->name }}</h6>
                        <div>
                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                            <span class="badge bg-{{ ($booking->payment_status ?? 'pending') === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($booking->payment_status ?? 'pending') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</p>
                                <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</p>
                                <p><strong>Guests:</strong> {{ $booking->guests }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h5 class="text-primary">${{ number_format($booking->total_price, 2) }}</h5>
                                <p class="text-muted">Booking #{{ $booking->id }}</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            @if(($booking->payment_status ?? 'pending') === 'pending' && $booking->status !== 'cancelled')
                            <button class="btn btn-success btn-sm" onclick="processPayment('hotel', {{ $booking->id }}, {{ $booking->total_price }})">
                                <i class="fas fa-credit-card me-1"></i>Pay Now
                            </button>
                            @endif
                            
                            @if($booking->status === 'confirmed' && ($booking->payment_status ?? 'pending') === 'paid')
                            <button class="btn btn-info btn-sm" onclick="downloadConfirmation('hotel', {{ $booking->id }})">
                                <i class="fas fa-download me-1"></i>Download Confirmation
                            </button>
                            @endif
                            
                            @if($booking->status !== 'cancelled' && \Carbon\Carbon::parse($booking->check_in)->isFuture())
                            <button class="btn btn-danger btn-sm" onclick="cancelBooking('hotel', {{ $booking->id }})">
                                <i class="fas fa-times me-1"></i>Cancel
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Ferry Tickets -->
        @if(isset($ferryTickets) && $ferryTickets->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <h4><i class="fas fa-ship me-2"></i>Ferry Tickets</h4>
                @foreach($ferryTickets as $ticket)
                <div class="card booking-card status-{{ $ticket->status }} payment-{{ $ticket->payment_status ?? 'pending' }} mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ $ticket->ferry->name }}</h6>
                        <div>
                            <span class="badge bg-{{ $ticket->status === 'confirmed' ? 'success' : ($ticket->status === 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                            <span class="badge bg-{{ ($ticket->payment_status ?? 'pending') === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($ticket->payment_status ?? 'pending') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Departure:</strong> {{ \Carbon\Carbon::parse($ticket->travel_date)->format('M d, Y H:i') }}</p>
                                <p><strong>Passengers:</strong> {{ $ticket->passengers }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h5 class="text-primary">${{ number_format($ticket->total_price, 2) }}</h5>
                                <p class="text-muted">Ticket #{{ $ticket->id }}</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            @if(($ticket->payment_status ?? 'pending') === 'pending' && $ticket->status !== 'cancelled')
                            <button class="btn btn-success btn-sm" onclick="processPayment('ferry', {{ $ticket->id }}, {{ $ticket->total_price }})">
                                <i class="fas fa-credit-card me-1"></i>Pay Now
                            </button>
                            @endif
                            
                            @if($ticket->status === 'confirmed' && ($ticket->payment_status ?? 'pending') === 'paid')
                            <button class="btn btn-info btn-sm" onclick="downloadConfirmation('ferry', {{ $ticket->id }})">
                                <i class="fas fa-download me-1"></i>Download Ticket
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Theme Park Tickets -->
        @if(isset($parkTickets) && $parkTickets->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <h4><i class="fas fa-ticket-alt me-2"></i>Theme Park Tickets</h4>
                @foreach($parkTickets as $ticket)
                <div class="card booking-card status-{{ $ticket->status }} payment-{{ $ticket->payment_status ?? 'pending' }} mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ $ticket->themePark->name }}</h6>
                        <div>
                            <span class="badge bg-{{ $ticket->status === 'confirmed' ? 'success' : ($ticket->status === 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                            <span class="badge bg-{{ ($ticket->payment_status ?? 'pending') === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($ticket->payment_status ?? 'pending') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Visit Date:</strong> {{ \Carbon\Carbon::parse($ticket->visit_date)->format('M d, Y') }}</p>
                                <p><strong>Tickets:</strong> {{ $ticket->quantity }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h5 class="text-primary">${{ number_format($ticket->total_price, 2) }}</h5>
                                <p class="text-muted">Ticket #{{ $ticket->id }}</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            @if(($ticket->payment_status ?? 'pending') === 'pending' && $ticket->status !== 'cancelled')
                            <button class="btn btn-success btn-sm" onclick="processPayment('park', {{ $ticket->id }}, {{ $ticket->total_price }})">
                                <i class="fas fa-credit-card me-1"></i>Pay Now
                            </button>
                            @endif
                            
                            @if($ticket->status === 'confirmed' && ($ticket->payment_status ?? 'pending') === 'paid')
                            <button class="btn btn-info btn-sm" onclick="downloadConfirmation('park', {{ $ticket->id }})">
                                <i class="fas fa-download me-1"></i>Download Ticket
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Beach Event Tickets -->
        @if(isset($beachTickets) && $beachTickets->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <h4><i class="fas fa-umbrella-beach me-2"></i>Beach Event Tickets</h4>
                @foreach($beachTickets as $ticket)
                <div class="card booking-card status-{{ $ticket->status }} payment-{{ $ticket->payment_status ?? 'pending' }} mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ $ticket->beachEvent->name }}</h6>
                        <div>
                            <span class="badge bg-{{ $ticket->status === 'confirmed' ? 'success' : ($ticket->status === 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                            <span class="badge bg-{{ ($ticket->payment_status ?? 'pending') === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($ticket->payment_status ?? 'pending') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Event Date:</strong> {{ \Carbon\Carbon::parse($ticket->event_date)->format('M d, Y H:i') }}</p>
                                <p><strong>Participants:</strong> {{ $ticket->participants }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h5 class="text-primary">${{ number_format($ticket->total_price, 2) }}</h5>
                                <p class="text-muted">Ticket #{{ $ticket->id }}</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            @if(($ticket->payment_status ?? 'pending') === 'pending' && $ticket->status !== 'cancelled')
                            <button class="btn btn-success btn-sm" onclick="processPayment('beach', {{ $ticket->id }}, {{ $ticket->total_price }})">
                                <i class="fas fa-credit-card me-1"></i>Pay Now
                            </button>
                            @endif
                            
                            @if($ticket->status === 'confirmed' && ($ticket->payment_status ?? 'pending') === 'paid')
                            <button class="btn btn-info btn-sm" onclick="downloadConfirmation('beach', {{ $ticket->id }})">
                                <i class="fas fa-download me-1"></i>Download Ticket
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if((!isset($hotelBookings) || $hotelBookings->count() == 0) && 
            (!isset($ferryTickets) || $ferryTickets->count() == 0) && 
            (!isset($parkTickets) || $parkTickets->count() == 0) && 
            (!isset($beachTickets) || $beachTickets->count() == 0))
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h4>No Bookings Found</h4>
            <p class="text-muted">You haven't made any bookings yet. Start exploring Paradise Island!</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Start Booking</a>
        </div>
        @endif
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-lock me-2"></i>Secure Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
                        <h5>Confirm Payment</h5>
                        <p class="text-muted">Amount: $<span id="paymentAmount">0.00</span></p>
                    </div>
                    <form id="paymentForm">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-credit-card me-1"></i>Card Number
                            </label>
                            <input type="text" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19" required>
                            <div class="form-text">Enter your 16-digit card number</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-user me-1"></i>Cardholder Name
                            </label>
                            <input type="text" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Expiry
                                </label>
                                <input type="text" class="form-control" placeholder="MM/YY" maxlength="5" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">
                                    <i class="fas fa-shield-alt me-1"></i>CVV
                                </label>
                                <input type="text" class="form-control" placeholder="123" maxlength="4" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    I agree to the <a href="#" class="text-decoration-none">terms and conditions</a>
                                </label>
                            </div>
                        </div>
                    </form>
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Secured by SSL encryption
                            </small>
                            <div>
                                <i class="fab fa-cc-visa fa-lg me-1"></i>
                                <i class="fab fa-cc-mastercard fa-lg me-1"></i>
                                <i class="fab fa-cc-amex fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" onclick="confirmPayment()">
                        <i class="fas fa-credit-card me-1"></i>Process Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentPaymentData = null;
        
        function processPayment(type, id, amount) {
            currentPaymentData = {
                type: 'single',
                bookings: [{type, id, amount}],
                total: amount
            };
            showPaymentModal(amount);
        }
        
        function processAllPayments(bookings, total) {
            currentPaymentData = {
                type: 'bulk',
                bookings: bookings,
                total: total
            };
            showPaymentModal(total);
        }
        
        function showPaymentModal(amount) {
            document.getElementById('paymentAmount').textContent = amount.toFixed(2);
            document.getElementById('paymentForm').reset();
            new bootstrap.Modal(document.getElementById('paymentModal')).show();
        }

        function confirmPayment() {
            // Show processing overlay
            showProcessingOverlay();
            
            // Simulate payment processing delay
            setTimeout(() => {
                processPaymentSuccess();
            }, 2000);
        }
        
        function showProcessingOverlay() {
            const modal = document.getElementById('paymentModal');
            const modalBody = modal.querySelector('.modal-body');
            const modalFooter = modal.querySelector('.modal-footer');
            
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Processing...</span>
                    </div>
                    <h5>Processing Payment...</h5>
                    <p class="text-muted">Please wait while we process your payment securely.</p>
                </div>
            `;
            modalFooter.style.display = 'none';
        }
        
        function processPaymentSuccess() {
            if (currentPaymentData.type === 'single') {
                // Single payment
                const booking = currentPaymentData.bookings[0];
                submitPayment(booking.type, booking.id);
            } else {
                // Bulk payment
                submitBulkPayment(currentPaymentData.bookings);
            }
        }
        
        function submitPayment(type, id) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/user/booking/${type}/${id}/pay`;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function submitBulkPayment(bookings) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/user/booking/pay-all';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const bookingsInput = document.createElement('input');
            bookingsInput.type = 'hidden';
            bookingsInput.name = 'bookings';
            bookingsInput.value = JSON.stringify(bookings);
            form.appendChild(bookingsInput);
            
            document.body.appendChild(form);
            form.submit();
        }

        function downloadConfirmation(type, id) {
            window.open(`/user/booking/${type}/${id}/confirmation`, '_blank');
        }

        function cancelBooking(type, id) {
            if (confirm('Are you sure you want to cancel this booking?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/user/booking/${type}/${id}/cancel`;
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Form validation for payment modal
        function validatePaymentForm() {
            const cardNumber = document.querySelector('input[placeholder="1234 5678 9012 3456"]').value;
            const expiry = document.querySelector('input[placeholder="MM/YY"]').value;
            const cvv = document.querySelector('input[placeholder="123"]').value;
            
            if (!cardNumber || !expiry || !cvv) {
                alert('Please fill in all payment details');
                return false;
            }
            
            // Basic card number validation (just check length)
            if (cardNumber.replace(/\s/g, '').length < 16) {
                alert('Please enter a valid card number');
                return false;
            }
            
            return true;
        }
        
        // Update confirm payment to include validation
        const originalConfirmPayment = confirmPayment;
        confirmPayment = function() {
            if (validatePaymentForm()) {
                originalConfirmPayment();
            }
        };
    </script>
</body>
</html>
