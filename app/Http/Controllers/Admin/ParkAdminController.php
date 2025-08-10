<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ThemePark;
use App\Models\ParkActivity;
use App\Models\ParkTicket;
use App\Models\ActivityTicket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ParkAdminController extends Controller
{
    public function management()
    {
        $admin = Auth::guard('admin')->user();
        
        // Verify admin is associated with a theme park
        if ($admin->business_type !== 'theme_park') {
            abort(403, 'Access denied. Theme park admin access required.');
        }

        $themePark = ThemePark::where('name', $admin->business_name)->first();
        
        if (!$themePark) {
            abort(404, 'Theme park not found.');
        }

        // Get today's statistics
        $today = now()->toDateString();
        $todaysTickets = ParkTicket::where('theme_park_id', $themePark->id)
            ->whereDate('created_at', $today)
            ->count();

        $todaysRevenue = ParkTicket::where('theme_park_id', $themePark->id)
            ->whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_price');

        $pendingPayments = ParkTicket::where('theme_park_id', $themePark->id)
            ->where('payment_status', 'pending')
            ->count();

        $paidTickets = ParkTicket::where('theme_park_id', $themePark->id)
            ->where('payment_status', 'paid')
            ->count();

        // Get activities for this theme park
        $activities = ParkActivity::where('theme_park_id', $themePark->id)->get();
        $activeActivities = $activities->where('is_active', true)->count();

        // Calculate current visitors (simplified - based on confirmed tickets for today)
        $currentVisitors = ParkTicket::where('theme_park_id', $themePark->id)
            ->whereDate('visit_date', $today)
            ->where('status', 'confirmed')
            ->sum('quantity');

        // Calculate capacity usage
        $totalCapacity = $themePark->capacity ?? 1000; // Default capacity
        $capacityUsage = ($totalCapacity > 0) 
            ? ($currentVisitors / $totalCapacity) * 100 
            : 0;

        // Get recent tickets
        $recentTickets = ParkTicket::where('theme_park_id', $themePark->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Add current bookings count to activities
        foreach ($activities as $activity) {
            $activity->current_bookings = ActivityTicket::where('park_activity_id', $activity->id)
                ->whereDate('created_at', $today)
                ->where('status', 'confirmed')
                ->count();
        }

        // Get upcoming events (simplified - we'll use a basic structure)
        $upcomingEvents = collect([
            (object) [
                'id' => 1,
                'title' => 'Summer Festival',
                'description' => 'Special summer celebration with live music and food stalls',
                'event_date' => now()->addDays(3),
                'event_time' => '14:00',
                'status' => 'active'
            ],
            (object) [
                'id' => 2,
                'title' => 'Night Show',
                'description' => 'Spectacular light and sound show',
                'event_date' => now()->addDays(7),
                'event_time' => '20:00',
                'status' => 'active'
            ]
        ]);

        return view('admin.park.management', compact(
            'todaysTickets',
            'todaysRevenue',
            'pendingPayments',
            'paidTickets',
            'currentVisitors',
            'activeActivities',
            'capacityUsage',
            'recentTickets',
            'activities',
            'upcomingEvents'
        ));
    }

    public function issueTicket(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'theme_park') {
            abort(403, 'Access denied.');
        }

        $themePark = ThemePark::where('name', $admin->business_name)->first();
        
        if (!$themePark) {
            abort(404, 'Theme park not found.');
        }

        $request->validate([
            'customer_email' => 'required|email',
            'ticket_type' => 'required|in:general,premium,vip',
            'visit_date' => 'required|date|after_or_equal:today',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        // Find or create user
        $user = User::where('email', $request->customer_email)->first();
        if (!$user) {
            return back()->withErrors(['customer_email' => 'Customer not found. Please ask them to register first.']);
        }

        // Calculate price based on ticket type
        $prices = [
            'general' => 25.00,
            'premium' => 45.00,
            'vip' => 75.00
        ];

        $unitPrice = $prices[$request->ticket_type];
        $totalPrice = $unitPrice * $request->quantity;

        // Create ticket
        $ticket = ParkTicket::create([
            'user_id' => $user->id,
            'theme_park_id' => $themePark->id,
            'ticket_code' => 'PARK-' . strtoupper(uniqid()),
            'ticket_type' => $request->ticket_type,
            'visit_date' => $request->visit_date,
            'quantity' => $request->quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'status' => 'confirmed', // Admin issued tickets are automatically confirmed
            'payment_status' => 'paid'
        ]);

        return redirect()->route('admin.park.management')
            ->with('success', "Ticket {$ticket->ticket_code} issued successfully for {$user->name}");
    }

    public function storeActivity(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'theme_park') {
            abort(403, 'Access denied.');
        }

        $themePark = ThemePark::where('name', $admin->business_name)->first();
        
        if (!$themePark) {
            abort(404, 'Theme park not found.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'duration' => 'required|integer|min:5',
            'is_active' => 'boolean'
        ]);

        ParkActivity::create([
            'theme_park_id' => $themePark->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'capacity' => $request->capacity,
            'duration' => $request->duration,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.park.management')
            ->with('success', 'Activity added successfully');
    }

    public function toggleActivity($activityId)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'theme_park') {
            return response()->json(['success' => false, 'message' => 'Access denied']);
        }

        $themePark = ThemePark::where('name', $admin->business_name)->first();
        
        if (!$themePark) {
            return response()->json(['success' => false, 'message' => 'Theme park not found']);
        }

        $activity = ParkActivity::where('id', $activityId)
            ->where('theme_park_id', $themePark->id)
            ->first();

        if (!$activity) {
            return response()->json(['success' => false, 'message' => 'Activity not found']);
        }

        $activity->is_active = !$activity->is_active;
        $activity->save();

        return response()->json(['success' => true, 'is_active' => $activity->is_active]);
    }

    public function reports()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'theme_park') {
            abort(403, 'Access denied.');
        }

        $themePark = ThemePark::where('name', $admin->business_name)->first();
        
        if (!$themePark) {
            abort(404, 'Theme park not found.');
        }

        // Generate reports data
        $monthlyRevenue = ParkTicket::where('theme_park_id', $themePark->id)
            ->where('status', 'confirmed')
            ->whereMonth('created_at', now()->month)
            ->sum('total_price');

        $monthlyVisitors = ParkTicket::where('theme_park_id', $themePark->id)
            ->where('status', 'confirmed')
            ->whereMonth('created_at', now()->month)
            ->sum('quantity');

        $popularActivities = ParkActivity::where('theme_park_id', $themePark->id)
            ->withCount(['activityTickets' => function($query) {
                $query->where('status', 'confirmed');
            }])
            ->orderBy('activity_tickets_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.park.reports', compact(
            'monthlyRevenue',
            'monthlyVisitors',
            'popularActivities'
        ));
    }

    public function validation()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'theme_park') {
            abort(403, 'Access denied.');
        }

        return view('admin.park.validation');
    }

    public function validateTicket(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string'
        ]);

        $admin = Auth::guard('admin')->user();
        $themePark = ThemePark::where('name', $admin->business_name)->first();

        $ticket = ParkTicket::where('ticket_code', $request->ticket_code)
            ->where('theme_park_id', $themePark->id)
            ->with('user')
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

        if ($ticket->visit_date !== now()->toDateString()) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket is not valid for today'
            ]);
        }

        return response()->json([
            'success' => true,
            'ticket' => [
                'code' => $ticket->ticket_code,
                'customer' => $ticket->user->name,
                'type' => $ticket->ticket_type,
                'quantity' => $ticket->quantity,
                'visit_date' => $ticket->visit_date
            ]
        ]);
    }

    public function ticketsList()
    {
        $admin = Auth::guard('admin')->user();
        
        $themePark = ThemePark::where('name', $admin->business_name)->first();
        
        if (!$themePark) {
            abort(404, 'Theme park not found.');
        }

        $parkTickets = ParkTicket::where('theme_park_id', $themePark->id)
            ->with(['user', 'themePark'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $activityTickets = ActivityTicket::whereHas('parkActivity', function($query) use ($themePark) {
            $query->where('theme_park_id', $themePark->id);
        })->with(['user', 'parkActivity'])
          ->orderBy('created_at', 'desc')
          ->paginate(20);

        return view('admin.park.tickets', compact('parkTickets', 'activityTickets'));
    }

    public function markAsPaid(Request $request, $id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin) {
                return redirect()->route('admin.login')->with('error', 'Please log in as an admin.');
            }
            
            \Log::info('Park admin attempting to mark ticket as paid', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'admin_business_type' => $admin->business_type,
                'admin_business_name' => $admin->business_name,
                'ticket_id' => $id
            ]);
            
            $themePark = ThemePark::where('name', $admin->business_name)->first();
            
            if (!$themePark) {
                return redirect()->back()->with('error', 'Theme park not found for your business.');
            }

            $ticket = ParkTicket::where('theme_park_id', $themePark->id)->findOrFail($id);

            if ($ticket->payment_status === 'paid') {
                return redirect()->back()->with('error', 'This ticket is already marked as paid.');
            }

            if ($ticket->status === 'cancelled') {
                return redirect()->back()->with('error', 'Cannot mark a cancelled ticket as paid.');
            }

            $ticket->status = 'confirmed';
            $ticket->payment_status = 'paid';
            $ticket->save();

            \Log::info('Park ticket marked as paid successfully', ['ticket_id' => $id]);
            
            return redirect()->back()->with('success', 'Park ticket marked as paid successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error marking park ticket as paid', [
                'ticket_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error marking ticket as paid: ' . $e->getMessage());
        }
    }

    public function markActivityAsPaid(Request $request, $id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin) {
                return redirect()->route('admin.login')->with('error', 'Please log in as an admin.');
            }
            
            \Log::info('Park admin attempting to mark activity ticket as paid', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'admin_business_type' => $admin->business_type,
                'admin_business_name' => $admin->business_name,
                'activity_ticket_id' => $id
            ]);
            
            $themePark = ThemePark::where('name', $admin->business_name)->first();
            
            if (!$themePark) {
                return redirect()->back()->with('error', 'Theme park not found for your business.');
            }

            $ticket = ActivityTicket::whereHas('parkActivity', function($query) use ($themePark) {
                $query->where('theme_park_id', $themePark->id);
            })->findOrFail($id);

            if ($ticket->payment_status === 'paid') {
                return redirect()->back()->with('error', 'This activity ticket is already marked as paid.');
            }

            if ($ticket->status === 'cancelled') {
                return redirect()->back()->with('error', 'Cannot mark a cancelled activity ticket as paid.');
            }

            $ticket->status = 'confirmed';
            $ticket->payment_status = 'paid';
            $ticket->save();

            \Log::info('Activity ticket marked as paid successfully', ['activity_ticket_id' => $id]);

            return redirect()->back()->with('success', 'Activity ticket marked as paid successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error marking activity ticket as paid', [
                'activity_ticket_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error marking activity ticket as paid: ' . $e->getMessage());
        }
    }

    public function cancelTicket(Request $request, $id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin) {
                return redirect()->route('admin.login')->with('error', 'Please log in as an admin.');
            }
            
            \Log::info('Park admin attempting to cancel ticket', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'admin_business_type' => $admin->business_type,
                'admin_business_name' => $admin->business_name,
                'ticket_id' => $id
            ]);
            
            $themePark = ThemePark::where('name', $admin->business_name)->first();
            
            if (!$themePark) {
                return redirect()->back()->with('error', 'Theme park not found for your business.');
            }

            $ticket = ParkTicket::where('theme_park_id', $themePark->id)->findOrFail($id);

            if ($ticket->status === 'cancelled') {
                return redirect()->back()->with('error', 'This ticket is already cancelled.');
            }

            $ticket->status = 'cancelled';
            $ticket->save();

            \Log::info('Park ticket cancelled successfully', ['ticket_id' => $id]);

            return redirect()->back()->with('success', 'Park ticket cancelled successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error cancelling park ticket', [
                'ticket_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error cancelling ticket: ' . $e->getMessage());
        }
    }

    public function cancelActivityTicket(Request $request, $id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin) {
                return redirect()->route('admin.login')->with('error', 'Please log in as an admin.');
            }
            
            \Log::info('Park admin attempting to cancel activity ticket', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'admin_business_type' => $admin->business_type,
                'admin_business_name' => $admin->business_name,
                'activity_ticket_id' => $id
            ]);
            
            $themePark = ThemePark::where('name', $admin->business_name)->first();
            
            if (!$themePark) {
                return redirect()->back()->with('error', 'Theme park not found for your business.');
            }

            $ticket = ActivityTicket::whereHas('parkActivity', function($query) use ($themePark) {
                $query->where('theme_park_id', $themePark->id);
            })->findOrFail($id);

            if ($ticket->status === 'cancelled') {
                return redirect()->back()->with('error', 'This activity ticket is already cancelled.');
            }

            $ticket->status = 'cancelled';
            $ticket->save();

            \Log::info('Activity ticket cancelled successfully', ['activity_ticket_id' => $id]);

            return redirect()->back()->with('success', 'Activity ticket cancelled successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error cancelling activity ticket', [
                'activity_ticket_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error cancelling activity ticket: ' . $e->getMessage());
        }
    }
}
