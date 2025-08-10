<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
        }
        .confirmation-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
        }
        .qr-code {
            width: 100px;
            height: 100px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="confirmation-header text-center mb-4">
                    <h1><i class="fas fa-island-tropical me-2"></i>Paradise Island</h1>
                    <h3>Booking Confirmation</h3>
                    <p class="mb-0">Thank you for choosing Paradise Island!</p>
                </div>

                <!-- Confirmation Details -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Booking Confirmed</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                @if($type === 'hotel')
                                    <h6>Hotel Reservation</h6>
                                    <p><strong>Hotel:</strong> {{ $booking->hotel->name }}</p>
                                    <p><strong>Address:</strong> {{ $booking->hotel->location }}</p>
                                    <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('F d, Y') }}</p>
                                    <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('F d, Y') }}</p>
                                    <p><strong>Guests:</strong> {{ $booking->guests }}</p>
                                    <p><strong>Room Type:</strong> {{ $booking->room_type ?? 'Standard' }}</p>
                                @elseif($type === 'ferry')
                                    <h6>Ferry Ticket</h6>
                                    <p><strong>Ferry:</strong> {{ $booking->ferry->name }}</p>
                                    <p><strong>Route:</strong> {{ $booking->ferry->route }}</p>
                                    <p><strong>Departure:</strong> {{ \Carbon\Carbon::parse($booking->departure_date)->format('F d, Y \a\t H:i') }}</p>
                                    <p><strong>Passengers:</strong> {{ $booking->passengers }}</p>
                                @elseif($type === 'park')
                                    <h6>Theme Park Ticket</h6>
                                    <p><strong>Park:</strong> {{ $booking->themePark->name }}</p>
                                    <p><strong>Location:</strong> {{ $booking->themePark->location }}</p>
                                    <p><strong>Visit Date:</strong> {{ \Carbon\Carbon::parse($booking->visit_date)->format('F d, Y') }}</p>
                                    <p><strong>Tickets:</strong> {{ $booking->quantity }}</p>
                                @elseif($type === 'activity')
                                    <h6>Activity Ticket</h6>
                                    <p><strong>Activity:</strong> {{ $booking->parkActivity->name }}</p>
                                    <p><strong>Description:</strong> {{ $booking->parkActivity->description }}</p>
                                    <p><strong>Visit Date:</strong> {{ \Carbon\Carbon::parse($booking->visit_date)->format('F d, Y') }}</p>
                                    <p><strong>Tickets:</strong> {{ $booking->quantity }}</p>
                                @elseif($type === 'beach')
                                    <h6>Beach Event Ticket</h6>
                                    <p><strong>Event:</strong> {{ $booking->beachEvent->name }}</p>
                                    <p><strong>Location:</strong> {{ $booking->beachEvent->location }}</p>
                                    <p><strong>Event Date:</strong> {{ \Carbon\Carbon::parse($booking->event_date)->format('F d, Y \a\t H:i') }}</p>
                                    <p><strong>Participants:</strong> {{ $booking->participants }}</p>
                                @endif
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="qr-code mx-auto mb-3">
                                    <i class="fas fa-qrcode fa-3x text-muted"></i>
                                </div>
                                <p class="small text-muted">QR Code for verification</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h6>Customer Information</h6>
                                <p><strong>Name:</strong> {{ $booking->user->name }}</p>
                                <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                                <p><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                                <p><strong>Booking Date:</strong> {{ $booking->created_at->format('F d, Y') }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h6>Payment Details</h6>
                                <p><strong>Amount Paid:</strong> ${{ number_format($booking->total_amount, 2) }}</p>
                                <p><strong>Payment Status:</strong> 
                                    <span class="badge bg-success">{{ ucfirst($booking->payment_status) }}</span>
                                </p>
                                <p><strong>Payment Date:</strong> {{ $booking->updated_at->format('F d, Y') }}</p>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <h6><i class="fas fa-info-circle me-2"></i>Important Information</h6>
                            <ul class="mb-0">
                                @if($type === 'hotel')
                                    <li>Please present this confirmation at check-in</li>
                                    <li>Check-in time: 3:00 PM | Check-out time: 11:00 AM</li>
                                    <li>Valid ID required at check-in</li>
                                @elseif($type === 'ferry')
                                    <li>Please arrive at the ferry terminal 30 minutes before departure</li>
                                    <li>Valid ID required for boarding</li>
                                    <li>Please present this ticket at the boarding gate</li>
                                @else
                                    <li>Please present this ticket at the entrance</li>
                                    <li>Valid ID required for verification</li>
                                    <li>Ticket is non-transferable</li>
                                @endif
                                <li>For questions, contact: info@paradiseisland.com</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4 no-print">
                    <button onclick="window.print()" class="btn btn-primary me-2">
                        <i class="fas fa-print me-1"></i>Print Confirmation
                    </button>
                    <a href="{{ route('hotel-bookings.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Back to Bookings
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
