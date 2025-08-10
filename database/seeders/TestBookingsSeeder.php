<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Ferry;
use App\Models\ThemePark;
use App\Models\BeachEvent;
use App\Models\HotelBooking;
use App\Models\FerryTicket;
use App\Models\ParkTicket;
use App\Models\BeachTicket;
use Illuminate\Support\Facades\Hash;

class TestBookingsSeeder extends Seeder
{
    public function run()
    {
        // Create or get a test user
        $testUser = User::firstOrCreate(
            ['email' => 'customer@test.com'],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('password'),
            ]
        );

        // Get business entities
        $hotel = Hotel::first();
        $ferry = Ferry::first();
        $themePark = ThemePark::first();
        $beachEvent = BeachEvent::first();

        if (!$hotel || !$ferry || !$themePark || !$beachEvent) {
            $this->command->error('Please run BusinessDataSeeder first to create hotels, ferries, theme parks, and beach events.');
            return;
        }

        // Create test hotel booking (unpaid)
        HotelBooking::create([
            'user_id' => $testUser->id,
            'hotel_id' => $hotel->id,
            'check_in' => now()->addDays(7)->format('Y-m-d'),
            'check_out' => now()->addDays(10)->format('Y-m-d'),
            'rooms' => 1,
            'guests' => 2,
            'total_price' => 2550.00, // 3 nights * $850
            'status' => 'pending',
            'special_requests' => 'Ocean view preferred',
        ]);

        // Create test ferry ticket (unpaid) - First need to get a hotel booking
        $hotelBooking = HotelBooking::where('user_id', $testUser->id)->first();
        
        FerryTicket::create([
            'user_id' => $testUser->id,
            'ferry_id' => $ferry->id,
            'hotel_booking_id' => $hotelBooking->id,
            'travel_date' => now()->addDays(7)->format('Y-m-d'),
            'passengers' => 2,
            'total_price' => 90.00, // 2 passengers * $45
            'status' => 'pending',
            'booking_reference' => 'FRY-' . strtoupper(uniqid()),
        ]);

        // Create test theme park ticket (unpaid)
        ParkTicket::create([
            'user_id' => $testUser->id,
            'theme_park_id' => $themePark->id,
            'hotel_booking_id' => $hotelBooking->id,
            'visit_date' => now()->addDays(8)->format('Y-m-d'),
            'visitors' => 2,
            'total_price' => 170.00, // 2 tickets * $85
            'status' => 'pending',
            'ticket_reference' => 'PRK-' . strtoupper(uniqid()),
        ]);

        // Create test beach event ticket (unpaid)
        BeachTicket::create([
            'user_id' => $testUser->id,
            'beach_event_id' => $beachEvent->id,
            'participants' => 2,
            'total_price' => 90.00, // 2 participants * $45
            'status' => 'pending',
            'ticket_reference' => 'BCH-' . strtoupper(uniqid()),
            'special_requests' => 'Vegetarian meal preferred',
        ]);

        // Create one paid booking for demonstration
        HotelBooking::create([
            'user_id' => $testUser->id,
            'hotel_id' => Hotel::skip(1)->first()->id ?? $hotel->id,
            'check_in' => now()->addDays(14)->format('Y-m-d'),
            'check_out' => now()->addDays(16)->format('Y-m-d'),
            'rooms' => 1,
            'guests' => 1,
            'total_price' => 1300.00, // 2 nights * $650
            'status' => 'confirmed',
            'special_requests' => 'Already paid',
        ]);

        $this->command->info('Test bookings created successfully!');
        $this->command->info('Test user credentials:');
        $this->command->info('Email: customer@test.com');
        $this->command->info('Password: password');
        $this->command->info('Total unpaid amount: $2900.00');
    }
}
