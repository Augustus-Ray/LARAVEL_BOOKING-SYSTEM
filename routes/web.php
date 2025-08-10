<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HotelBookingController;
use App\Http\Controllers\FerryController;
use App\Http\Controllers\FerryTicketController;
use App\Http\Controllers\ThemeParkController;
use App\Http\Controllers\ParkTicketController;
use App\Http\Controllers\ActivityTicketController;
use App\Http\Controllers\BeachEventController;
use App\Http\Controllers\BeachTicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Homepage routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/map', [HomeController::class, 'map'])->name('map');

// Hotel routes
Route::resource('hotels', HotelController::class);

// Hotel booking routes
Route::middleware(['auth'])->group(function () {
    Route::get('/hotels/{hotel}/book', [HotelBookingController::class, 'create'])->name('hotels.book');
    Route::post('/hotels/{hotel}/book', [HotelBookingController::class, 'store'])->name('hotels.booking.store');
    Route::resource('hotel-bookings', HotelBookingController::class)->except(['create', 'store']);
    
    // Payment processing routes
    Route::post('/process-payment/hotel/{id}', [App\Http\Controllers\User\UserBookingController::class, 'processPayment'])->name('payment.process.hotel');
    Route::post('/process-all-payments', [App\Http\Controllers\User\UserBookingController::class, 'processAllPayments'])->name('payment.process.all');
});

// Ferry and Theme Park viewing (open to everyone)
Route::resource('ferries', FerryController::class)->only(['index', 'show']);
Route::resource('theme-parks', ThemeParkController::class)->only(['index', 'show']);

// Routes that require hotel booking (booking actions only)
Route::middleware(['auth', 'require.hotel.booking'])->group(function () {
    // Ferry booking routes
    Route::get('/ferries/{ferry}/book', [FerryTicketController::class, 'create'])->name('ferries.book');
    Route::post('/ferries/{ferry}/book', [FerryTicketController::class, 'store'])->name('ferries.booking.store');
    Route::resource('ferry-tickets', FerryTicketController::class)->except(['create', 'store']);
    
    // Theme park booking routes
    Route::get('/theme-parks/{themePark}/book', [ParkTicketController::class, 'create'])->name('parks.book');
    Route::post('/theme-parks/{themePark}/book', [ParkTicketController::class, 'store'])->name('parks.booking.store');
    Route::resource('park-tickets', ParkTicketController::class)->except(['create', 'store']);
    
    // Activity booking routes
    Route::get('/activities/{parkActivity}/book', [ActivityTicketController::class, 'create'])->name('activities.book');
    Route::post('/activities/{parkActivity}/book', [ActivityTicketController::class, 'store'])->name('activities.booking.store');
    Route::resource('activity-tickets', ActivityTicketController::class)->except(['create', 'store']);
});

// Ferry ticket management (for users with existing bookings)
Route::middleware(['auth'])->group(function () {
    Route::post('/ferry-tickets/{ferryTicket}/cancel', [FerryTicketController::class, 'cancel'])->name('ferry-tickets.cancel');
    Route::post('/hotel-bookings/{hotelBooking}/cancel', [HotelBookingController::class, 'cancel'])->name('hotel-bookings.cancel');
    Route::post('/park-tickets/{parkTicket}/cancel', [ParkTicketController::class, 'cancel'])->name('park-tickets.cancel');
    Route::post('/activity-tickets/{activityTicket}/cancel', [ActivityTicketController::class, 'cancel'])->name('activity-tickets.cancel');
});

// Beach events (no hotel booking required)
Route::resource('beach-events', BeachEventController::class);
Route::middleware(['auth'])->group(function () {
    Route::get('/beach-events/{beachEvent}/book', [BeachTicketController::class, 'create'])->name('beach-events.book');
    Route::post('/beach-events/{beachEvent}/book', [BeachTicketController::class, 'store'])->name('beach-events.booking.store');
    Route::resource('beach-tickets', BeachTicketController::class)->except(['create', 'store']);
});

// Custom authentication routes using simple views
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return view('admin.login');
    })->middleware('guest')->name('admin.login');
    
    Route::post('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->middleware('guest')->name('admin.login.submit');
    
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/manage-bookings', [App\Http\Controllers\Admin\AdminController::class, 'manageBookings'])->name('admin.manage-bookings');
        Route::post('/booking/{type}/{id}/update', [App\Http\Controllers\Admin\AdminController::class, 'updateBookingStatus'])->name('admin.booking.update');
        
        // Hotel Admin Routes
        Route::post('/hotel/rooms', [App\Http\Controllers\Admin\HotelAdminController::class, 'storeRoom'])->name('admin.hotel.rooms.store');
        Route::post('/hotel/rooms/{room}/toggle', [App\Http\Controllers\Admin\HotelAdminController::class, 'toggleRoomAvailability'])->name('admin.hotel.rooms.toggle');
        Route::get('/hotel/reports', [App\Http\Controllers\Admin\HotelAdminController::class, 'reports'])->name('admin.hotel.reports');
        Route::get('/hotel/promotions', [App\Http\Controllers\Admin\HotelAdminController::class, 'promotions'])->name('admin.hotel.promotions');
        Route::get('/hotel/bookings', [App\Http\Controllers\Admin\HotelAdminController::class, 'bookingsList'])->name('admin.hotel.bookings');
        Route::post('/hotel/bookings/{id}/mark-paid', [App\Http\Controllers\Admin\HotelAdminController::class, 'markAsPaid'])->name('admin.hotel.bookings.mark-paid');
        Route::post('/hotel/bookings/{id}/cancel', [App\Http\Controllers\Admin\HotelAdminController::class, 'cancelBooking'])->name('admin.hotel.bookings.cancel');
        
        // Ferry Admin Routes
        Route::get('/ferry/operations', [App\Http\Controllers\Admin\FerryAdminController::class, 'operations'])->name('admin.ferry.operations');
        Route::get('/ferry/validation', [App\Http\Controllers\Admin\FerryAdminController::class, 'validation'])->name('admin.ferry.validation');
        Route::get('/ferry/schedules', [App\Http\Controllers\Admin\FerryAdminController::class, 'schedules'])->name('admin.ferry.schedules');
        Route::post('/ferry/issue-ticket', [App\Http\Controllers\Admin\FerryAdminController::class, 'issueTicket'])->name('admin.ferry.issue-ticket');
        Route::post('/ferry/validate-ticket', [App\Http\Controllers\Admin\FerryAdminController::class, 'validateTicket'])->name('admin.ferry.validate-ticket');
        Route::get('/ferry/passengers', [App\Http\Controllers\Admin\FerryAdminController::class, 'passengerList'])->name('admin.ferry.passengers');
        Route::get('/ferry/tickets', [App\Http\Controllers\Admin\FerryAdminController::class, 'ticketsList'])->name('admin.ferry.tickets');
        Route::post('/ferry/tickets/{id}/mark-paid', [App\Http\Controllers\Admin\FerryAdminController::class, 'markAsPaid'])->name('admin.ferry.tickets.mark-paid');
        Route::post('/ferry/tickets/{id}/cancel', [App\Http\Controllers\Admin\FerryAdminController::class, 'cancelTicket'])->name('admin.ferry.tickets.cancel');
        
        // Theme Park Admin Routes
        Route::post('/park/issue-ticket', [App\Http\Controllers\Admin\ParkAdminController::class, 'issueTicket'])->name('admin.park.issue-ticket');
        Route::post('/park/activities', [App\Http\Controllers\Admin\ParkAdminController::class, 'storeActivity'])->name('admin.park.activities.store');
        Route::post('/park/activities/{activity}/toggle', [App\Http\Controllers\Admin\ParkAdminController::class, 'toggleActivity'])->name('admin.park.activities.toggle');
        Route::get('/park/validation', [App\Http\Controllers\Admin\ParkAdminController::class, 'validation'])->name('admin.park.validation');
        Route::post('/park/validate-ticket', [App\Http\Controllers\Admin\ParkAdminController::class, 'validateTicket'])->name('admin.park.validate-ticket');
        Route::get('/park/reports', [App\Http\Controllers\Admin\ParkAdminController::class, 'reports'])->name('admin.park.reports');
        Route::get('/park/tickets', [App\Http\Controllers\Admin\ParkAdminController::class, 'ticketsList'])->name('admin.park.tickets');
        Route::post('/park/tickets/{id}/mark-paid', [App\Http\Controllers\Admin\ParkAdminController::class, 'markAsPaid'])->name('admin.park.tickets.mark-paid');
        Route::post('/park/tickets/{id}/cancel', [App\Http\Controllers\Admin\ParkAdminController::class, 'cancelTicket'])->name('admin.park.tickets.cancel');
        Route::post('/park/activity-tickets/{id}/mark-paid', [App\Http\Controllers\Admin\ParkAdminController::class, 'markActivityAsPaid'])->name('admin.park.activity-tickets.mark-paid');
        Route::post('/park/activity-tickets/{id}/cancel', [App\Http\Controllers\Admin\ParkAdminController::class, 'cancelActivityTicket'])->name('admin.park.activity-tickets.cancel');
        
        // Beach Admin Routes
        Route::get('/beach/events', [App\Http\Controllers\Admin\BeachAdminController::class, 'events'])->name('admin.beach.events');
        Route::get('/beach/reports', [App\Http\Controllers\Admin\BeachAdminController::class, 'reports'])->name('admin.beach.reports');
        Route::get('/beach/tickets', [App\Http\Controllers\Admin\BeachAdminController::class, 'ticketsList'])->name('admin.beach.tickets');
        Route::post('/beach/tickets/{id}/mark-paid', [App\Http\Controllers\Admin\BeachAdminController::class, 'markAsPaid'])->name('admin.beach.tickets.mark-paid');
        Route::post('/beach/tickets/{id}/cancel', [App\Http\Controllers\Admin\BeachAdminController::class, 'cancelTicket'])->name('admin.beach.tickets.cancel');
        
        // System Admin Routes
        Route::get('/system/dashboard', [App\Http\Controllers\Admin\SystemAdminController::class, 'dashboard'])->name('admin.system.dashboard');
        Route::get('/system/users', [App\Http\Controllers\Admin\SystemAdminController::class, 'userManagement'])->name('admin.system.users');
        Route::get('/system/businesses', [App\Http\Controllers\Admin\SystemAdminController::class, 'businessManagement'])->name('admin.system.businesses');
        Route::post('/system/users', [App\Http\Controllers\Admin\SystemAdminController::class, 'createUser'])->name('admin.system.users.create');
        Route::post('/system/users/{user}/suspend', [App\Http\Controllers\Admin\SystemAdminController::class, 'suspendUser'])->name('admin.system.users.suspend');
        Route::get('/system/export/users', [App\Http\Controllers\Admin\SystemAdminController::class, 'exportUsers'])->name('admin.system.export.users');
        Route::get('/system/reports', [App\Http\Controllers\Admin\SystemAdminController::class, 'systemReports'])->name('admin.system.reports');
        Route::post('/system/settings', [App\Http\Controllers\Admin\SystemAdminController::class, 'updateSiteSettings'])->name('admin.system.settings');
        
        Route::post('/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

// Super Admin routes
Route::prefix('super-admin')->group(function () {
    Route::get('/login', function () {
        return view('super-admin.login');
    })->middleware('guest')->name('super-admin.login');
    
    Route::post('/login', [App\Http\Controllers\SuperAdmin\SuperAdminAuthController::class, 'login'])->middleware('guest')->name('super-admin.login.submit');
    
    Route::middleware('auth:super_admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'dashboard'])->name('super-admin.dashboard');
        Route::get('/manage-admins', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'manageAdmins'])->name('super-admin.manage-admins');
        Route::post('/create-admin', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'createAdmin'])->name('super-admin.create-admin');
        Route::delete('/delete-admin/{admin}', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'deleteAdmin'])->name('super-admin.delete-admin');
        Route::get('/bookings', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'bookings'])->name('super-admin.bookings');
        Route::post('/logout', [App\Http\Controllers\SuperAdmin\SuperAdminAuthController::class, 'logout'])->name('super-admin.logout');
    });
});

// Dashboard and profile routes
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User booking management routes
    Route::get('/my-bookings', [App\Http\Controllers\User\UserBookingController::class, 'index'])->name('user.bookings');
    
    // Redirect old user.bookings to hotel-bookings cart
    Route::redirect('/user-bookings', '/hotel-bookings', 301);
    Route::redirect('/my-bookings', '/hotel-bookings', 301);
    Route::post('/user/booking/{type}/{id}/pay', [App\Http\Controllers\User\UserBookingController::class, 'processPayment'])->name('user.booking.pay');
    Route::post('/user/booking/pay-all', [App\Http\Controllers\User\UserBookingController::class, 'processAllPayments'])->name('user.booking.pay-all');
    Route::get('/user/booking/{type}/{id}/confirmation', [App\Http\Controllers\User\UserBookingController::class, 'downloadConfirmation'])->name('user.booking.confirmation');
    Route::post('/user/booking/{type}/{id}/cancel', [App\Http\Controllers\User\UserBookingController::class, 'cancelBooking'])->name('user.booking.cancel');
});

// Include authentication routes for email verification and other auth features
require __DIR__.'/auth.php';
