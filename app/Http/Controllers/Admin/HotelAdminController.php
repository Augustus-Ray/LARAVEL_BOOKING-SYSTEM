<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hotel;
use App\Models\HotelBooking;

class HotelAdminController extends Controller
{
    public function management()
    {
        $admin = Auth::guard('admin')->user();
        
        $stats = [
            'total_bookings' => HotelBooking::whereHas('hotel', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->count(),
            'confirmed_bookings' => HotelBooking::whereHas('hotel', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->where('status', 'confirmed')->count(),
            'pending_bookings' => HotelBooking::whereHas('hotel', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->where('status', 'pending')->count(),
            'paid_bookings' => HotelBooking::whereHas('hotel', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->where('payment_status', 'paid')->count(),
            'pending_payments' => HotelBooking::whereHas('hotel', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->where('payment_status', 'pending')->count(),
            'total_revenue' => HotelBooking::whereHas('hotel', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->where('payment_status', 'paid')->sum('total_price'),
        ];

        $recentBookings = HotelBooking::whereHas('hotel', function($query) use ($admin) {
            $query->where('name', $admin->business_name);
        })->with('user', 'hotel')->latest()->take(10)->get();

        return view('admin.hotel.management', compact('admin', 'stats', 'recentBookings'));
    }

    public function reports()
    {
        $admin = Auth::guard('admin')->user();

        $query = HotelBooking::whereHas('hotel', function($q) use ($admin) {
            $q->where('name', $admin->business_name);
        });

        $stats = [
            'total_bookings' => $query->count(),
            'total_revenue' => $query->where('payment_status', 'paid')->sum('total_price'),
            'monthly_revenue' => $query->where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->sum('total_price'),
            'pending_payments' => $query->where('payment_status', 'pending')->count(),
            'paid_bookings' => $query->where('payment_status', 'paid')->count(),
            'occupancy_rate' => 75, // Mock data
            'avg_stay' => 3, // Mock data
        ];

        $recent_bookings = $query->with('user', 'hotel')->latest()->take(10)->get();

        return view('admin.hotel.reports', compact('admin', 'recent_bookings', 'stats'));
    }

    public function promotions()
    {
        $admin = Auth::guard('admin')->user();
        
        $stats = [
            'active_promotions' => 2,
            'used_promotions' => 15,
            'promotion_revenue' => 5250.00
        ];

        $promotions = collect([
            [
                'id' => 1,
                'title' => 'Early Bird Special',
                'description' => '20% off for bookings made 30 days in advance',
                'discount_percentage' => 20,
                'start_date' => '2025-08-01',
                'end_date' => '2025-08-31',
                'is_active' => true
            ],
            [
                'id' => 2,
                'title' => 'Weekend Getaway',
                'description' => '15% off on weekend stays',
                'discount_percentage' => 15,
                'start_date' => '2025-07-01',
                'end_date' => '2025-12-31',
                'is_active' => true
            ]
        ]);

        return view('admin.hotel.promotions', compact('admin', 'promotions', 'stats'));
    }

    public function bookingsList()
    {
        $admin = Auth::guard('admin')->user();
        
        $bookings = HotelBooking::whereHas('hotel', function($query) use ($admin) {
            $query->where('name', $admin->business_name);
        })->with(['user', 'hotel'])
          ->orderBy('created_at', 'desc')
          ->paginate(20);

        return view('admin.hotel.bookings', compact('bookings'));
    }

    public function markAsPaid(Request $request, $id)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin) {
                return redirect()->route('admin.login')->with('error', 'Please log in as an admin.');
            }
            
            \Log::info('Admin attempting to mark booking as paid', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'admin_business_type' => $admin->business_type,
                'admin_business_name' => $admin->business_name,
                'booking_id' => $id
            ]);
            
            $booking = HotelBooking::whereHas('hotel', function($query) use ($admin) {
                $query->where('name', $admin->business_name);
            })->findOrFail($id);

            if ($booking->payment_status === 'paid') {
                return redirect()->back()->with('error', 'This booking is already marked as paid.');
            }

            if ($booking->status === 'cancelled') {
                return redirect()->back()->with('error', 'Cannot mark a cancelled booking as paid.');
            }

            $booking->status = 'confirmed';
            $booking->payment_status = 'paid';
            
            // Add note about manual payment
            if ($booking->special_requests) {
                $booking->special_requests .= ' | MANUALLY MARKED AS PAID by ' . $admin->name . ' on ' . now()->format('Y-m-d H:i:s');
            } else {
                $booking->special_requests = 'MANUALLY MARKED AS PAID by ' . $admin->name . ' on ' . now()->format('Y-m-d H:i:s');
            }
            
            $booking->save();

            \Log::info('Booking marked as paid successfully', ['booking_id' => $id]);
            
            return redirect()->back()->with('success', 'Booking marked as paid successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error marking booking as paid', [
                'booking_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error marking booking as paid: ' . $e->getMessage());
        }
    }

    public function cancelBooking(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        
        $booking = HotelBooking::whereHas('hotel', function($query) use ($admin) {
            $query->where('name', $admin->business_name);
        })->findOrFail($id);

        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'This booking is already cancelled.');
        }

        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }
}