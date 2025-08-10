<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BeachEvent;
use App\Models\BeachTicket;
use Illuminate\Support\Facades\Auth;

class BeachAdminController extends Controller
{
    public function management()
    {
        $admin = Auth::guard('admin')->user();
        
        // Verify admin is associated with beach events
        if ($admin->business_type !== 'beach_event') {
            abort(403, 'Access denied. Beach event admin access required.');
        }

        // Get today's statistics
        $today = now()->toDateString();
        $todaysTickets = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
            $query->where('organizer', $admin->business_name);
        })->whereDate('created_at', $today)->count();

        $todaysRevenue = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
            $query->where('organizer', $admin->business_name);
        })->whereDate('created_at', $today)
          ->where('payment_status', 'paid')
          ->sum('price');

        $pendingPayments = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
            $query->where('organizer', $admin->business_name);
        })->where('payment_status', 'pending')->count();

        $paidTickets = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
            $query->where('organizer', $admin->business_name);
        })->where('payment_status', 'paid')->count();

        $totalTickets = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
            $query->where('organizer', $admin->business_name);
        })->count();

        // Get upcoming events
        $upcomingEvents = BeachEvent::where('organizer', $admin->business_name)
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(5)
            ->get();

        // Get recent tickets
        $recentTickets = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
            $query->where('organizer', $admin->business_name);
        })->with(['user', 'beachEvent'])
          ->orderBy('created_at', 'desc')
          ->take(10)
          ->get();

        // Calculate some stats
        $totalRevenue = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
            $query->where('organizer', $admin->business_name);
        })->where('payment_status', 'paid')->sum('price');

        $monthlyRevenue = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
            $query->where('organizer', $admin->business_name);
        })->where('payment_status', 'paid')
          ->whereMonth('created_at', now()->month)
          ->sum('price');

        return view('admin.beach.management', compact(
            'todaysTickets',
            'todaysRevenue',
            'pendingPayments',
            'paidTickets',
            'totalTickets',
            'totalRevenue',
            'monthlyRevenue',
            'upcomingEvents',
            'recentTickets'
        ));
    }

    public function events()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'beach_event') {
            abort(403, 'Access denied.');
        }

        $events = BeachEvent::where('organizer', $admin->business_name)
            ->orderBy('event_date', 'desc')
            ->paginate(10);

        return view('admin.beach.events', compact('events'));
    }

    public function reports()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'beach_event') {
            abort(403, 'Access denied.');
        }

        $query = BeachTicket::whereHas('beachEvent', function($q) use ($admin) {
            $q->where('organizer', $admin->business_name);
        });

        $stats = [
            'total_tickets' => $query->count(),
            'total_revenue' => $query->where('payment_status', 'paid')->sum('price'),
            'monthly_revenue' => $query->where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->sum('price'),
            'pending_payments' => $query->where('payment_status', 'pending')->count(),
            'paid_tickets' => $query->where('payment_status', 'paid')->count(),
            'avg_ticket_price' => $query->where('payment_status', 'paid')->avg('price'),
        ];

        $recent_tickets = $query->with('user', 'beachEvent')->latest()->take(10)->get();

        return view('admin.beach.reports', compact('admin', 'recent_tickets', 'stats'));
    }

    public function ticketsList()
    {
        $admin = Auth::guard('admin')->user();
        
        $tickets = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
            $query->where('organizer', $admin->business_name);
        })->with(['user', 'beachEvent'])
          ->orderBy('created_at', 'desc')
          ->paginate(20);

        return view('admin.beach.tickets', compact('tickets'));
    }

    public function markAsPaid(Request $request, $id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin) {
                return redirect()->route('admin.login')->with('error', 'Please log in as an admin.');
            }
            
            \Log::info('Beach admin attempting to mark ticket as paid', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'admin_business_type' => $admin->business_type,
                'admin_business_name' => $admin->business_name,
                'ticket_id' => $id
            ]);
            
            $beachEvent = BeachEvent::where('organizer_name', $admin->business_name)->first();
            
            if (!$beachEvent) {
                return redirect()->back()->with('error', 'Beach event not found for your business.');
            }

            $ticket = BeachTicket::where('beach_event_id', $beachEvent->id)->findOrFail($id);

            if ($ticket->payment_status === 'paid') {
                return redirect()->back()->with('error', 'This ticket is already marked as paid.');
            }

            if ($ticket->status === 'cancelled') {
                return redirect()->back()->with('error', 'Cannot mark a cancelled ticket as paid.');
            }

            $ticket->status = 'confirmed';
            $ticket->payment_status = 'paid';
            $ticket->save();

            \Log::info('Beach ticket marked as paid successfully', ['ticket_id' => $id]);
            
            return redirect()->back()->with('success', 'Beach ticket marked as paid successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error marking beach ticket as paid', [
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
        
        $ticket = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
            $query->where('organizer', $admin->business_name);
        })->findOrFail($id);

        if ($ticket->status === 'cancelled') {
            return redirect()->back()->with('error', 'This ticket is already cancelled.');
        }

        $ticket->status = 'cancelled';
        $ticket->save();

        return redirect()->back()->with('success', 'Beach event ticket cancelled successfully.');
    }
}
