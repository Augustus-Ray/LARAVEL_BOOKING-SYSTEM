<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HotelBooking;
use App\Models\FerryTicket;
use App\Models\ParkTicket;
use App\Models\ActivityTicket;
use App\Models\BeachTicket;

class UserBookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get pending bookings (unpaid items for cart)
        $hotelBookings = HotelBooking::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('payment_status', 'pending')
            ->with('hotel')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $ferryTickets = FerryTicket::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('payment_status', 'pending')
            ->with('ferry')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $parkTickets = ParkTicket::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('payment_status', 'pending')
            ->with('themePark')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $activityTickets = ActivityTicket::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('payment_status', 'pending')
            ->with('parkActivity')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $beachTickets = BeachTicket::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('payment_status', 'pending')
            ->with('beachEvent')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.bookings', compact(
            'hotelBookings', 
            'ferryTickets', 
            'parkTickets', 
            'activityTickets',
            'beachTickets'
        ));
    }

    public function allBookings()
    {
        $user = Auth::user();
        
        // Get all bookings regardless of status
        $hotelBookings = HotelBooking::where('user_id', $user->id)
            ->with('hotel')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $ferryTickets = FerryTicket::where('user_id', $user->id)
            ->with('ferry')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $parkTickets = ParkTicket::where('user_id', $user->id)
            ->with('themePark')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $activityTickets = ActivityTicket::where('user_id', $user->id)
            ->with('parkActivity')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $beachTickets = BeachTicket::where('user_id', $user->id)
            ->with('beachEvent')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.all-bookings', compact(
            'hotelBookings', 
            'ferryTickets', 
            'parkTickets', 
            'activityTickets',
            'beachTickets'
        ));
    }

    public function processPayment(Request $request, $type, $id)
    {
        $user = Auth::user();
        $booking = null;

        switch ($type) {
            case 'hotel':
                $booking = HotelBooking::where('user_id', $user->id)->findOrFail($id);
                break;
            case 'ferry':
                $booking = FerryTicket::where('user_id', $user->id)->findOrFail($id);
                break;
            case 'park':
                $booking = ParkTicket::where('user_id', $user->id)->findOrFail($id);
                break;
            case 'activity':
                $booking = ActivityTicket::where('user_id', $user->id)->findOrFail($id);
                break;
            case 'beach':
                $booking = BeachTicket::where('user_id', $user->id)->findOrFail($id);
                break;
            default:
                return redirect()->back()->with('error', 'Invalid booking type.');
        }

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found.');
        }

        if ($booking->status === 'confirmed') {
            return redirect()->back()->with('error', 'This booking is already confirmed and paid.');
        }

        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Cannot pay for a cancelled booking.');
        }

        // Simulate payment processing - mark as confirmed/paid
        $booking->status = 'confirmed';
        $booking->payment_status = 'paid';
        
        // Only update special_requests for booking types that have this column
        if (in_array($type, ['hotel', 'beach'])) {
            $booking->special_requests = ($booking->special_requests ? $booking->special_requests . ' | ' : '') . 'PAID on ' . now()->format('Y-m-d H:i:s');
        }
        
        $booking->save();

        return redirect()->route('hotel-bookings.index')->with('success', 'Payment processed successfully! Your booking is now confirmed.');
    }

    public function processAllPayments(Request $request)
    {
        $user = Auth::user();
        $bookingsData = json_decode($request->input('bookings'), true);
        
        if (!$bookingsData || !is_array($bookingsData)) {
            return redirect()->back()->with('error', 'Invalid payment data.');
        }

        $successCount = 0;
        $totalAmount = 0;
        $failedBookings = [];

        foreach ($bookingsData as $bookingData) {
            try {
                $booking = null;
                $type = $bookingData['type'];
                $id = $bookingData['id'];

                switch ($type) {
                    case 'hotel':
                        $booking = HotelBooking::where('user_id', $user->id)->findOrFail($id);
                        break;
                    case 'ferry':
                        $booking = FerryTicket::where('user_id', $user->id)->findOrFail($id);
                        break;
                    case 'park':
                        $booking = ParkTicket::where('user_id', $user->id)->findOrFail($id);
                        break;
                    case 'activity':
                        $booking = ActivityTicket::where('user_id', $user->id)->findOrFail($id);
                        break;
                    case 'beach':
                        $booking = BeachTicket::where('user_id', $user->id)->findOrFail($id);
                        break;
                    default:
                        $failedBookings[] = "Invalid booking type: {$type}";
                        continue 2;
                }

                if ($booking && $booking->status === 'pending') {
                    // Simulate payment processing - mark as confirmed/paid
                    $booking->status = 'confirmed';
                    $booking->payment_status = 'paid';
                    
                    // Only update special_requests for booking types that have this column
                    if (in_array($type, ['hotel', 'beach'])) {
                        $booking->special_requests = ($booking->special_requests ? $booking->special_requests . ' | ' : '') . 'PAID on ' . now()->format('Y-m-d H:i:s');
                    }
                    
                    $booking->save();
                    
                    $successCount++;
                    $totalAmount += $booking->total_price;
                } else {
                    $failedBookings[] = "Booking {$type} #{$id} is not eligible for payment";
                }
            } catch (\Exception $e) {
                $failedBookings[] = "Failed to process {$type} booking #{$id}";
            }
        }

        if ($successCount > 0) {
            $message = "Successfully processed {$successCount} payment" . ($successCount > 1 ? 's' : '') . " totaling $" . number_format($totalAmount, 2) . "!";
            
            if (count($failedBookings) > 0) {
                $message .= " Some bookings could not be processed: " . implode(', ', $failedBookings);
            }
            
            return redirect()->route('hotel-bookings.index')->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'No payments could be processed. ' . implode(', ', $failedBookings));
        }
    }

    public function downloadConfirmation($type, $id)
    {
        $user = Auth::user();
        $booking = null;

        switch ($type) {
            case 'hotel':
                $booking = HotelBooking::where('user_id', $user->id)->with('hotel')->findOrFail($id);
                break;
            case 'ferry':
                $booking = FerryTicket::where('user_id', $user->id)->with('ferry')->findOrFail($id);
                break;
            case 'park':
                $booking = ParkTicket::where('user_id', $user->id)->with('themePark')->findOrFail($id);
                break;
            case 'activity':
                $booking = ActivityTicket::where('user_id', $user->id)->with('parkActivity')->findOrFail($id);
                break;
            case 'beach':
                $booking = BeachTicket::where('user_id', $user->id)->with('beachEvent')->findOrFail($id);
                break;
            default:
                return redirect()->back()->with('error', 'Invalid booking type.');
        }

        if (!$booking || $booking->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Confirmation not available.');
        }

        return view('user.confirmation', compact('booking', 'type'));
    }

    public function cancelBooking(Request $request, $type, $id)
    {
        $user = Auth::user();
        $booking = null;

        switch ($type) {
            case 'hotel':
                $booking = HotelBooking::where('user_id', $user->id)->findOrFail($id);
                break;
            case 'ferry':
                $booking = FerryTicket::where('user_id', $user->id)->findOrFail($id);
                break;
            case 'park':
                $booking = ParkTicket::where('user_id', $user->id)->findOrFail($id);
                break;
            case 'activity':
                $booking = ActivityTicket::where('user_id', $user->id)->findOrFail($id);
                break;
            case 'beach':
                $booking = BeachTicket::where('user_id', $user->id)->findOrFail($id);
                break;
            default:
                return redirect()->back()->with('error', 'Invalid booking type.');
        }

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found.');
        }

        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'This booking is already cancelled.');
        }

        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('hotel-bookings.index')->with('success', 'Booking cancelled successfully.');
    }
}
