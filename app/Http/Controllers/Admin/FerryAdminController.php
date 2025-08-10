<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ferry;
use App\Models\FerryTicket;
use App\Models\HotelBooking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FerryAdminController extends Controller
{
    public function operations()
    {
        $admin = Auth::guard('admin')->user();
        
        // Verify admin is associated with a ferry
        if ($admin->business_type !== 'ferry') {
            abort(403, 'Access denied. Ferry admin access required.');
        }

        $ferry = Ferry::where('name', $admin->business_name)->first();
        
        if (!$ferry) {
            abort(404, 'Ferry not found.');
        }

        // Get today's statistics
        $today = now()->toDateString();
        $todaysTickets = FerryTicket::where('ferry_id', $ferry->id)
            ->whereDate('departure_date', $today)
            ->count();

        $todaysRevenue = FerryTicket::where('ferry_id', $ferry->id)
            ->whereDate('departure_date', $today)
            ->where('payment_status', 'paid')
            ->sum('price');

        $currentPassengers = FerryTicket::where('ferry_id', $ferry->id)
            ->whereDate('departure_date', $today)
            ->where('status', 'confirmed')
            ->count();

        $pendingPayments = FerryTicket::where('ferry_id', $ferry->id)
            ->where('payment_status', 'pending')
            ->count();

        $paidTickets = FerryTicket::where('ferry_id', $ferry->id)
            ->where('payment_status', 'paid')
            ->count();

        // Calculate capacity usage
        $capacityUsage = ($ferry && $ferry->capacity > 0) 
            ? ($currentPassengers / $ferry->capacity) * 100 
            : 0;

        // Calculate additional ferry metrics
        $todayDepartures = 3; // Number of scheduled departures today
        $bookedSeats = $currentPassengers;
        $availableSeats = ($ferry ? $ferry->capacity : 0) - $bookedSeats;

        // Get recent tickets
        $recentTickets = FerryTicket::where('ferry_id', $ferry->id)
            ->with(['user', 'hotelBooking.hotel'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get upcoming departures
        $upcomingDepartures = collect([
            (object) [
                'time' => '08:00',
                'destination' => 'Main Island',
                'passengers' => 45,
                'capacity' => $ferry->capacity
            ],
            (object) [
                'time' => '10:30',
                'destination' => 'Beach Resort',
                'passengers' => 32,
                'capacity' => $ferry->capacity
            ],
            (object) [
                'time' => '14:00',
                'destination' => 'Main Island',
                'passengers' => 28,
                'capacity' => $ferry->capacity
            ]
        ]);

        return view('admin.ferry.operations', compact(
            'ferry',
            'todaysTickets',
            'todaysRevenue',
            'currentPassengers',
            'pendingPayments',
            'paidTickets',
            'capacityUsage',
            'recentTickets',
            'upcomingDepartures',
            'todayDepartures',
            'bookedSeats',
            'availableSeats'
        ));
    }

    public function validation()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'ferry') {
            abort(403, 'Access denied.');
        }

        return view('admin.ferry.validation');
    }

    public function schedules()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'ferry') {
            abort(403, 'Access denied.');
        }

        $ferry = Ferry::where('name', $admin->business_name)->first();

        // Get this week's schedules
        $schedules = collect([
            (object) [
                'id' => 1,
                'date' => now()->format('Y-m-d'),
                'time' => '08:00',
                'route' => 'Island to Mainland',
                'status' => 'active',
                'passengers' => 45
            ],
            (object) [
                'id' => 2,
                'date' => now()->format('Y-m-d'),
                'time' => '10:30',
                'route' => 'Mainland to Island',
                'status' => 'active',
                'passengers' => 32
            ],
            (object) [
                'id' => 3,
                'date' => now()->addDay()->format('Y-m-d'),
                'time' => '09:00',
                'route' => 'Island to Mainland',
                'status' => 'scheduled',
                'passengers' => 0
            ]
        ]);

        return view('admin.ferry.schedules', compact('ferry', 'schedules'));
    }

    public function issueTicket(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'ferry') {
            abort(403, 'Access denied.');
        }

        $ferry = Ferry::where('name', $admin->business_name)->first();
        
        if (!$ferry) {
            abort(404, 'Ferry not found.');
        }

        $request->validate([
            'customer_email' => 'required|email',
            'departure_date' => 'required|date|after_or_equal:today',
            'departure_time' => 'required',
            'hotel_booking_id' => 'nullable|exists:hotel_bookings,id'
        ]);

        // Find user
        $user = User::where('email', $request->customer_email)->first();
        if (!$user) {
            return back()->withErrors(['customer_email' => 'Customer not found. Please ask them to register first.']);
        }

        // Validate hotel booking if provided
        $hotelBooking = null;
        if ($request->hotel_booking_id) {
            $hotelBooking = HotelBooking::where('id', $request->hotel_booking_id)
                ->where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->first();
            
            if (!$hotelBooking) {
                return back()->withErrors(['hotel_booking_id' => 'Invalid hotel booking']);
            }
        }

        // Create ferry ticket
        $ticket = FerryTicket::create([
            'user_id' => $user->id,
            'ferry_id' => $ferry->id,
            'hotel_booking_id' => $hotelBooking ? $hotelBooking->id : null,
            'departure_date' => $request->departure_date,
            'departure_time' => $request->departure_time,
            'price' => $ferry->price,
            'ticket_number' => 'FERRY-' . strtoupper(uniqid()),
            'status' => 'confirmed'
        ]);

        return redirect()->route('admin.ferry.operations')
            ->with('success', "Ferry ticket {$ticket->ticket_number} issued successfully for {$user->name}");
    }

    public function validateTicket(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string'
        ]);

        $admin = Auth::guard('admin')->user();
        $ferry = Ferry::where('name', $admin->business_name)->first();

        $ticket = FerryTicket::where('ticket_number', $request->ticket_number)
            ->where('ferry_id', $ferry->id)
            ->with(['user', 'hotelBooking.hotel'])
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found or invalid'
            ]);
        }

        if ($ticket->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Ticket is not confirmed'
            ]);
        }

        if ($ticket->departure_date !== now()->toDateString()) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket is not valid for today'
            ]);
        }

        $response = [
            'success' => true,
            'ticket' => [
                'number' => $ticket->ticket_number,
                'passenger' => $ticket->user->name,
                'departure_date' => $ticket->departure_date,
                'departure_time' => $ticket->departure_time,
                'price' => $ticket->price
            ]
        ];

        if ($ticket->hotelBooking) {
            $response['hotel_booking'] = [
                'hotel' => $ticket->hotelBooking->hotel->name,
                'check_in' => $ticket->hotelBooking->check_in_date,
                'check_out' => $ticket->hotelBooking->check_out_date,
                'status' => $ticket->hotelBooking->status
            ];
        }

        return response()->json($response);
    }

    public function passengerList(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'ferry') {
            abort(403, 'Access denied.');
        }

        $ferry = Ferry::where('name', $admin->business_name)->first();
        
        $date = $request->get('date', now()->toDateString());
        $time = $request->get('time');

        $query = FerryTicket::where('ferry_id', $ferry->id)
            ->whereDate('departure_date', $date)
            ->where('status', 'confirmed')
            ->with(['user', 'hotelBooking.hotel']);

        if ($time) {
            $query->where('departure_time', $time);
        }

        $passengers = $query->get();

        return response()->json([
            'passengers' => $passengers->map(function ($ticket) {
                return [
                    'name' => $ticket->user->name,
                    'email' => $ticket->user->email,
                    'ticket_number' => $ticket->ticket_number,
                    'departure_time' => $ticket->departure_time,
                    'hotel' => $ticket->hotelBooking ? $ticket->hotelBooking->hotel->name : 'No hotel booking'
                ];
            })
        ]);
    }

    public function ticketsList()
    {
        $admin = Auth::guard('admin')->user();
        
        $ferry = Ferry::where('name', $admin->business_name)->first();
        
        if (!$ferry) {
            abort(404, 'Ferry not found.');
        }

        $tickets = FerryTicket::where('ferry_id', $ferry->id)
            ->with(['user', 'ferry', 'hotelBooking.hotel'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.ferry.tickets', compact('tickets'));
    }

    public function markAsPaid(Request $request, $id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin) {
                return redirect()->route('admin.login')->with('error', 'Please log in as an admin.');
            }
            
            \Log::info('Ferry admin attempting to mark ticket as paid', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'admin_business_type' => $admin->business_type,
                'admin_business_name' => $admin->business_name,
                'ticket_id' => $id
            ]);
            
            $ferry = Ferry::where('name', $admin->business_name)->first();
            
            if (!$ferry) {
                return redirect()->back()->with('error', 'Ferry not found for your business.');
            }

            $ticket = FerryTicket::where('ferry_id', $ferry->id)->findOrFail($id);

            if ($ticket->payment_status === 'paid') {
                return redirect()->back()->with('error', 'This ticket is already marked as paid.');
            }

            if ($ticket->status === 'cancelled') {
                return redirect()->back()->with('error', 'Cannot mark a cancelled ticket as paid.');
            }

            $ticket->status = 'confirmed';
            $ticket->payment_status = 'paid';
            $ticket->save();

            \Log::info('Ferry ticket marked as paid successfully', ['ticket_id' => $id]);
            
            return redirect()->back()->with('success', 'Ferry ticket marked as paid successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error marking ferry ticket as paid', [
                'ticket_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error marking ticket as paid: ' . $e->getMessage());
        }
    }

    public function cancelTicket(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        $ferry = Ferry::where('name', $admin->business_name)->first();
        
        if (!$ferry) {
            abort(404, 'Ferry not found.');
        }

        $ticket = FerryTicket::where('ferry_id', $ferry->id)->findOrFail($id);

        if ($ticket->status === 'cancelled') {
            return redirect()->back()->with('error', 'This ticket is already cancelled.');
        }

        $ticket->status = 'cancelled';
        $ticket->save();

        return redirect()->back()->with('success', 'Ferry ticket cancelled successfully.');
    }
}
