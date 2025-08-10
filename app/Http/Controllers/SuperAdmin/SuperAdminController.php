<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\SuperAdmin;
use App\Models\HotelBooking;
use App\Models\FerryTicket;
use App\Models\ParkTicket;
use App\Models\ActivityTicket;
use App\Models\BeachTicket;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => Admin::count(),
            'hotel_bookings' => HotelBooking::count(),
            'ferry_tickets' => FerryTicket::count(),
            'park_tickets' => ParkTicket::count(),
            'activity_tickets' => ActivityTicket::count(),
            'beach_tickets' => BeachTicket::count(),
            'total_revenue' => $this->calculateTotalRevenue(),
        ];

        return view('super-admin.dashboard', compact('stats'));
    }

    public function manageAdmins()
    {
        $admins = Admin::with('createdBy')->get();
        $hotels = \App\Models\Hotel::all();
        $ferries = \App\Models\Ferry::all();
        $themeParks = \App\Models\ThemePark::all();
        $beachEvents = \App\Models\BeachEvent::all();
        
        return view('super-admin.manage-admins', compact('admins', 'hotels', 'ferries', 'themeParks', 'beachEvents'));
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'business_type' => 'required|in:general,hotel,ferry,theme_park,beach_event',
            'business_id' => 'nullable|integer',
            'permissions' => 'array',
        ]);

        $permissions = $request->permissions ?? [];
        
        // Add default permissions based on business type
        if ($request->business_type !== 'general') {
            $permissions[] = 'manage_' . $request->business_type . '_bookings';
            $permissions[] = 'view_' . $request->business_type . '_stats';
        }

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'business_type' => $request->business_type,
            'business_id' => $request->business_id,
            'permissions' => array_unique($permissions),
            'created_by' => Auth::guard('super_admin')->id(),
        ]);

        return redirect()->route('super-admin.manage-admins')
                        ->with('success', 'Admin created successfully.');
    }

    public function deleteAdmin(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('super-admin.manage-admins')
                        ->with('success', 'Admin deleted successfully.');
    }

    public function bookings()
    {
        $bookings = [
            'hotels' => HotelBooking::with('user', 'hotel')->latest()->paginate(10),
            'ferries' => FerryTicket::with('user', 'ferry')->latest()->paginate(10),
            'parks' => ParkTicket::with('user', 'themePark')->latest()->paginate(10),
            'activities' => ActivityTicket::with('user', 'parkActivity')->latest()->paginate(10),
            'beaches' => BeachTicket::with('user', 'beachEvent')->latest()->paginate(10),
        ];

        return view('super-admin.bookings', compact('bookings'));
    }

    private function calculateTotalRevenue()
    {
        $hotelRevenue = HotelBooking::sum('total_amount');
        $ferryRevenue = FerryTicket::sum('total_amount');
        $parkRevenue = ParkTicket::sum('total_amount');
        $activityRevenue = ActivityTicket::sum('total_amount');
        $beachRevenue = BeachTicket::sum('total_amount');

        return $hotelRevenue + $ferryRevenue + $parkRevenue + $activityRevenue + $beachRevenue;
    }
}
