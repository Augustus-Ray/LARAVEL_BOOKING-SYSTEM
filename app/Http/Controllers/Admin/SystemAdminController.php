<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Hotel;
use App\Models\Ferry;
use App\Models\ThemePark;
use App\Models\BeachEvent;
use App\Models\HotelBooking;
use App\Models\FerryTicket;
use App\Models\ParkTicket;
use App\Models\BeachTicket;
use Illuminate\Support\Facades\Auth;

class SystemAdminController extends Controller
{
    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        
        // Verify admin is a system administrator
        if ($admin->business_type !== 'system') {
            abort(403, 'Access denied. System administrator access required.');
        }

        // Get system statistics
        $totalUsers = User::count();
        $totalBusinesses = Admin::where('business_type', '!=', 'system')->count();
        
        // Calculate total bookings across all services
        $hotelBookings = HotelBooking::where('status', 'confirmed')->count();
        $ferryTickets = FerryTicket::where('status', 'confirmed')->count();
        $parkTickets = ParkTicket::where('status', 'confirmed')->count();
        $beachTickets = BeachTicket::where('status', 'confirmed')->count();
        $totalBookings = $hotelBookings + $ferryTickets + $parkTickets + $beachTickets;

        // Calculate total revenue
        $hotelRevenue = HotelBooking::where('status', 'confirmed')->sum('total_price');
        $ferryRevenue = FerryTicket::where('status', 'confirmed')->sum('price');
        $parkRevenue = ParkTicket::where('status', 'confirmed')->sum('total_price');
        $beachRevenue = BeachTicket::where('status', 'confirmed')->sum('price');
        $totalRevenue = $hotelRevenue + $ferryRevenue + $parkRevenue + $beachRevenue;

        // Get users with pagination
        $users = User::withCount([
            'hotelBookings as total_bookings' => function($query) {
                $query->where('status', 'confirmed');
            }
        ])->orderBy('created_at', 'desc')->paginate(10);

        // Business statistics
        $businessStats = [
            'hotels' => [
                'count' => Hotel::count(),
                'revenue' => $hotelRevenue
            ],
            'ferries' => [
                'count' => Ferry::count(),
                'revenue' => $ferryRevenue
            ],
            'parks' => [
                'count' => ThemePark::count(),
                'revenue' => $parkRevenue
            ],
            'beaches' => [
                'count' => BeachEvent::count(),
                'revenue' => $beachRevenue
            ]
        ];

        // Popular services data
        $popularServices = [
            'hotels' => $hotelBookings,
            'ferries' => $ferryTickets,
            'parks' => $parkTickets,
            'beaches' => $beachTickets
        ];

        return view('admin.system.dashboard', compact(
            'totalUsers',
            'totalBusinesses',
            'totalBookings',
            'totalRevenue',
            'users',
            'businessStats',
            'popularServices'
        ));
    }

    public function userManagement()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'system') {
            abort(403, 'Access denied.');
        }

        $users = User::withCount([
            'hotelBookings',
            'ferryTickets',
            'parkTickets',
            'beachTickets'
        ])->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.system.users', compact('users'));
    }

    public function businessManagement()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'system') {
            abort(403, 'Access denied.');
        }

        $businesses = Admin::where('business_type', '!=', 'system')
            ->orderBy('created_at', 'desc')
            ->get();

        $hotels = Hotel::with('admin')->get();
        $ferries = Ferry::with('admin')->get();
        $themeParks = ThemePark::with('admin')->get();
        $beachEvents = BeachEvent::with('admin')->get();

        return view('admin.system.businesses', compact(
            'businesses',
            'hotels',
            'ferries',
            'themeParks',
            'beachEvents'
        ));
    }

    public function createUser(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'system') {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'user_type' => 'required|in:customer,admin'
        ]);

        if ($request->user_type === 'admin') {
            $request->validate([
                'business_type' => 'required|in:hotel,ferry,theme_park,beach',
                'business_name' => 'required|string|max:255'
            ]);

            Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'business_type' => $request->business_type,
                'business_name' => $request->business_name
            ]);

            return redirect()->route('admin.system.dashboard')
                ->with('success', 'Admin user created successfully');
        } else {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'email_verified_at' => now()
            ]);

            return redirect()->route('admin.system.dashboard')
                ->with('success', 'Customer user created successfully');
        }
    }

    public function suspendUser($userId)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'system') {
            abort(403, 'Access denied.');
        }

        $user = User::findOrFail($userId);
        
        // Toggle suspension status (you would need to add a 'suspended' column to users table)
        // For now, we'll just return success
        
        return response()->json([
            'success' => true,
            'message' => 'User suspension status updated'
        ]);
    }

    public function exportUsers()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'system') {
            abort(403, 'Access denied.');
        }

        $users = User::all();
        
        $csvData = "ID,Name,Email,Created At,Email Verified\n";
        foreach ($users as $user) {
            $csvData .= "{$user->id},{$user->name},{$user->email},{$user->created_at},{$user->email_verified_at}\n";
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="users_export.csv"');
    }

    public function systemReports()
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'system') {
            abort(403, 'Access denied.');
        }

        // Generate comprehensive system reports
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $month->format('M Y'),
                'users' => User::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)->count(),
                'bookings' => HotelBooking::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->where('status', 'confirmed')->count(),
                'revenue' => HotelBooking::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->where('status', 'confirmed')->sum('total_price')
            ];
        }

        return view('admin.system.reports', compact('monthlyStats'));
    }

    public function updateSiteSettings(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if ($admin->business_type !== 'system') {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'site_name' => 'required|string|max:255',
            'maintenance_mode' => 'boolean',
            'platform_commission' => 'required|numeric|min:0|max:20'
        ]);

        // In a real application, you would store these in a settings table or config
        // For now, we'll just return success
        
        return redirect()->route('admin.system.dashboard')
            ->with('success', 'Site settings updated successfully');
    }
}
