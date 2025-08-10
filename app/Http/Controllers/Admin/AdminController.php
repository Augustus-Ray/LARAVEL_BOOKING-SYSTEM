<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HotelBooking;
use App\Models\FerryTicket;
use App\Models\ParkTicket;
use App\Models\ActivityTicket;
use App\Models\BeachTicket;

class AdminController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        $stats = [
            'hotel_bookings' => 0,
            'ferry_tickets' => 0,
            'park_tickets' => 0,
            'activity_tickets' => 0,
            'beach_tickets' => 0,
        ];
        $recent_bookings = [];
        
        // Get statistics based on admin's business type
        if ($admin->business_type === 'hotel') {
            // Hotel admin can only see hotel bookings
            $stats['hotel_bookings'] = HotelBooking::whereHas('hotel', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->count();
            
            $recent_bookings['hotels'] = HotelBooking::whereHas('hotel', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->with('user', 'hotel')->latest()->take(5)->get();
            
        } elseif ($admin->business_type === 'ferry') {
            // Ferry admin can only see ferry tickets
            $stats['ferry_tickets'] = FerryTicket::whereHas('ferry', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->count();
            
            $recent_bookings['ferries'] = FerryTicket::whereHas('ferry', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->with('user', 'ferry')->latest()->take(5)->get();
            
        } elseif ($admin->business_type === 'theme_park') {
            // Theme park admin can only see park tickets
            $stats['park_tickets'] = ParkTicket::whereHas('themePark', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->count();
            
            $recent_bookings['parks'] = ParkTicket::whereHas('themePark', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->with('user', 'themePark')->latest()->take(5)->get();
            
        } elseif ($admin->business_type === 'beach') {
            // Beach admin can only see beach tickets
            $stats['beach_tickets'] = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->count();
            
            $recent_bookings['beaches'] = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->with('user', 'beachEvent')->latest()->take(5)->get();
            
        } else {
            // General or system admin can see all bookings
            $stats['hotel_bookings'] = HotelBooking::count();
            $stats['ferry_tickets'] = FerryTicket::count();
            $stats['park_tickets'] = ParkTicket::count();
            $stats['beach_tickets'] = BeachTicket::count();
            
            $recent_bookings['hotels'] = HotelBooking::with('user', 'hotel')->latest()->take(5)->get();
            $recent_bookings['ferries'] = FerryTicket::with('user', 'ferry')->latest()->take(5)->get();
            $recent_bookings['parks'] = ParkTicket::with('user', 'themePark')->latest()->take(5)->get();
            $recent_bookings['beaches'] = BeachTicket::with('user', 'beachEvent')->latest()->take(5)->get();
        }
        
        return view('admin.dashboard', compact('admin', 'stats', 'recent_bookings'));
    }

    public function manageBookings(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $type = $request->get('type', 'all');
        
        $bookings = collect(); // Initialize as empty collection
        
        // Get bookings based on admin's business type
        if ($admin->business_type === 'hotel') {
            $bookings = HotelBooking::whereHas('hotel', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->with('user', 'hotel')->orderBy('created_at', 'desc')->paginate(10);
            $type = 'hotel';
            
        } elseif ($admin->business_type === 'ferry') {
            $bookings = FerryTicket::whereHas('ferry', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->with('user', 'ferry')->orderBy('created_at', 'desc')->paginate(10);
            $type = 'ferry';
            
        } elseif ($admin->business_type === 'theme_park') {
            $bookings = ParkTicket::whereHas('themePark', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->with('user', 'themePark')->orderBy('created_at', 'desc')->paginate(10);
            $type = 'park';
            
        } elseif ($admin->business_type === 'beach') {
            $bookings = BeachTicket::whereHas('beachEvent', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->with('user', 'beachEvent')->orderBy('created_at', 'desc')->paginate(10);
            $type = 'beach';
            
        } else {
            // General or system admin - show based on requested type
            if ($type === 'hotel' || $type === 'all') {
                $bookings = HotelBooking::with('user', 'hotel')->orderBy('created_at', 'desc')->paginate(20);
                $type = 'hotel';
            } elseif ($type === 'ferry') {
                $bookings = FerryTicket::with('user', 'ferry')->orderBy('created_at', 'desc')->paginate(20);
            } elseif ($type === 'theme_park') {
                $bookings = ParkTicket::with('user', 'themePark')->orderBy('created_at', 'desc')->paginate(20);
            } elseif ($type === 'beach_event') {
                $bookings = BeachTicket::with('user', 'beachEvent')->orderBy('created_at', 'desc')->paginate(20);
            } else {
                $bookings = HotelBooking::with('user', 'hotel')->orderBy('created_at', 'desc')->paginate(20);
                $type = 'hotel';
            }
        }

        return view('admin.manage-bookings', compact('bookings', 'admin', 'type'));
    }

    public function updateBookingStatus(Request $request, $type, $id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin) {
                return redirect()->route('admin.login')->with('error', 'Please log in as an admin.');
            }
            
            \Log::info('Admin attempting to update booking status', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'admin_business_type' => $admin->business_type,
                'admin_business_name' => $admin->business_name,
                'booking_type' => $type,
                'booking_id' => $id,
                'action' => $request->get('action'),
                'status' => $request->get('status')
            ]);
            
            // Handle different actions
            $action = $request->get('action');
            
            if ($action) {
                // New action-based system
                $booking = null;
                
                switch ($type) {
                    case 'hotel':
                        $booking = HotelBooking::findOrFail($id);
                        break;
                    case 'ferry':
                        $booking = FerryTicket::findOrFail($id);
                        break;
                    case 'theme_park':
                        $booking = ParkTicket::findOrFail($id);
                        break;
                    case 'beach_event':
                        $booking = BeachTicket::findOrFail($id);
                        break;
                }
                
                if (!$booking) {
                    return redirect()->back()->with('error', 'Booking not found');
                }
                
                switch ($action) {
                    case 'mark_paid':
                        if ($booking->payment_status === 'paid') {
                            return redirect()->back()->with('error', 'This booking is already marked as paid.');
                        }
                        if ($booking->status === 'cancelled') {
                            return redirect()->back()->with('error', 'Cannot mark a cancelled booking as paid.');
                        }
                        $booking->payment_status = 'paid';
                        $booking->status = 'confirmed';
                        $booking->save();
                        \Log::info('Booking marked as paid successfully', ['booking_type' => $type, 'booking_id' => $id]);
                        return redirect()->back()->with('success', 'Booking marked as paid successfully');
                        
                    case 'confirm':
                        $booking->status = 'confirmed';
                        $booking->save();
                        \Log::info('Booking confirmed successfully', ['booking_type' => $type, 'booking_id' => $id]);
                        return redirect()->back()->with('success', 'Booking confirmed successfully');
                        
                    case 'cancel':
                        $booking->status = 'cancelled';
                        $booking->save();
                        \Log::info('Booking cancelled successfully', ['booking_type' => $type, 'booking_id' => $id]);
                        return redirect()->back()->with('success', 'Booking cancelled successfully');
                        
                    default:
                        return redirect()->back()->with('error', 'Invalid action');
                }
            } else {
                // Legacy status-based system
                $request->validate([
                    'status' => 'required|in:confirmed,cancelled,pending'
                ]);

                $booking = null;
                
                switch ($type) {
                    case 'hotel':
                        $booking = HotelBooking::findOrFail($id);
                        break;
                    case 'ferry':
                        $booking = FerryTicket::findOrFail($id);
                        break;
                    case 'theme_park':
                        $booking = ParkTicket::findOrFail($id);
                        break;
                    case 'beach_event':
                        $booking = BeachTicket::findOrFail($id);
                        break;
                }

                if ($booking) {
                    $booking->status = $request->status;
                    $booking->save();
                    \Log::info('Booking status updated successfully', ['booking_type' => $type, 'booking_id' => $id, 'new_status' => $request->status]);
                    return redirect()->back()->with('success', 'Booking status updated successfully');
                }
                
                return redirect()->back()->with('error', 'Booking not found');
            }
            
        } catch (\Exception $e) {
            \Log::error('Error updating booking status', [
                'booking_type' => $type,
                'booking_id' => $id,
                'action' => $request->get('action'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error updating booking: ' . $e->getMessage());
        }
    }
}