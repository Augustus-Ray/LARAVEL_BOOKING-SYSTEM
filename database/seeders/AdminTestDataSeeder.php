<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\SuperAdmin;
use App\Models\Hotel;
use App\Models\Ferry;
use App\Models\ThemePark;
use App\Models\HotelBooking;
use App\Models\FerryTicket;
use App\Models\ParkTicket;

class AdminTestDataSeeder extends Seeder
{
    public function run()
    {
        // First create super admin
        $superAdmin = SuperAdmin::firstOrCreate([
            'email' => 'superadmin@paradiseisland.com'
        ], [
            'name' => 'Super Administrator',
            'password' => bcrypt('password123')
        ]);

        // Create a test customer
        $user = User::firstOrCreate([
            'email' => 'customer@test.com'
        ], [
            'name' => 'Test Customer',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);

        // Create admin users for each business type
        $generalAdmin = Admin::firstOrCreate([
            'email' => 'admin@paradiseisland.com'
        ], [
            'name' => 'General Administrator',
            'password' => bcrypt('admin123'),
            'business_type' => 'system',
            'business_name' => 'Paradise Island',
            'created_by' => $superAdmin->id
        ]);

        $hotelAdmin = Admin::firstOrCreate([
            'email' => 'hotel@paradiseisland.com'
        ], [
            'name' => 'Hotel Manager',
            'password' => bcrypt('hotel123'),
            'business_type' => 'hotel',
            'business_name' => 'Paradise Resort',
            'created_by' => $superAdmin->id
        ]);

        $ferryAdmin = Admin::firstOrCreate([
            'email' => 'ferry@paradiseisland.com'
        ], [
            'name' => 'Ferry Operator',
            'password' => bcrypt('ferry123'),
            'business_type' => 'ferry',
            'business_name' => 'Island Express',
            'created_by' => $superAdmin->id
        ]);

        $parkAdmin = Admin::firstOrCreate([
            'email' => 'themepark@paradiseisland.com'
        ], [
            'name' => 'Park Manager',
            'password' => bcrypt('park123'),
            'business_type' => 'theme_park',
            'business_name' => 'Adventure World',
            'created_by' => $superAdmin->id
        ]);

        $beachAdmin = Admin::firstOrCreate([
            'email' => 'beach@paradiseisland.com'
        ], [
            'name' => 'Beach Organizer',
            'password' => bcrypt('beach123'),
            'business_type' => 'beach',
            'business_name' => 'Paradise Beach Events',
            'created_by' => $superAdmin->id
        ]);

        // Create businesses
        $hotel = Hotel::firstOrCreate([
            'name' => 'Paradise Resort'
        ], [
            'description' => 'Beautiful beachfront resort with stunning ocean views',
            'location' => 'Maldives Beach',
            'price_per_night' => 150.00,
            'total_rooms' => 50,
            'available_rooms' => 45,
            'owner_id' => $user->id
        ]);

        $ferry = Ferry::firstOrCreate([
            'name' => 'Island Express'
        ], [
            'departure_location' => 'Main Port',
            'arrival_location' => 'Paradise Island',
            'departure_time' => '09:00:00',
            'arrival_time' => '10:30:00',
            'capacity' => 100,
            'price' => 25.00,
            'operating_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
            'operator_id' => $user->id
        ]);

        $themePark = ThemePark::firstOrCreate([
            'name' => 'Adventure World'
        ], [
            'description' => 'Thrilling theme park experience with exciting rides',
            'location' => 'Paradise Island',
            'entry_price' => 35.00,
            'opening_time' => '09:00:00',
            'closing_time' => '18:00:00',
            'operating_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
            'capacity' => 500,
            'owner_id' => $user->id
        ]);

        // Create sample bookings
        $hotelBooking = HotelBooking::firstOrCreate([
            'user_id' => $user->id,
            'hotel_id' => $hotel->id,
            'check_in' => now()->addDays(3)->format('Y-m-d')
        ], [
            'check_out' => now()->addDays(7)->format('Y-m-d'),
            'rooms' => 1,
            'guests' => 2,
            'total_price' => 600.00,
            'status' => 'confirmed'
        ]);

        FerryTicket::firstOrCreate([
            'user_id' => $user->id,
            'ferry_id' => $ferry->id,
            'hotel_booking_id' => $hotelBooking->id,
            'booking_reference' => 'FERRY-TEST123'
        ], [
            'travel_date' => now()->addDays(2)->format('Y-m-d'),
            'passengers' => 2,
            'total_price' => 50.00,
            'status' => 'confirmed'
        ]);

        ParkTicket::firstOrCreate([
            'user_id' => $user->id,
            'theme_park_id' => $themePark->id,
            'hotel_booking_id' => $hotelBooking->id,
            'ticket_reference' => 'PARK-TEST123'
        ], [
            'visit_date' => now()->addDays(5)->format('Y-m-d'),
            'visitors' => 2,
            'total_price' => 70.00,
            'status' => 'confirmed'
        ]);

        // Create additional bookings for better testing
        for ($i = 1; $i <= 3; $i++) {
            $additionalUser = User::firstOrCreate([
                'email' => "customer{$i}@test.com"
            ], [
                'name' => "Test Customer {$i}",
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]);

            $additionalBooking = HotelBooking::create([
                'user_id' => $additionalUser->id,
                'hotel_id' => $hotel->id,
                'check_in' => now()->addDays(rand(1, 30))->format('Y-m-d'),
                'check_out' => now()->addDays(rand(31, 60))->format('Y-m-d'),
                'rooms' => rand(1, 3),
                'guests' => rand(1, 4),
                'total_price' => rand(200, 800),
                'status' => ['confirmed', 'pending', 'cancelled'][rand(0, 2)]
            ]);

            FerryTicket::create([
                'user_id' => $additionalUser->id,
                'ferry_id' => $ferry->id,
                'hotel_booking_id' => $additionalBooking->id,
                'travel_date' => now()->addDays(rand(1, 30))->format('Y-m-d'),
                'passengers' => rand(1, 4),
                'total_price' => 25.00 * rand(1, 4),
                'booking_reference' => 'FERRY-' . strtoupper(uniqid()),
                'status' => ['confirmed', 'pending'][rand(0, 1)]
            ]);

            ParkTicket::create([
                'user_id' => $additionalUser->id,
                'theme_park_id' => $themePark->id,
                'hotel_booking_id' => $additionalBooking->id,
                'ticket_reference' => 'PARK-' . strtoupper(uniqid()),
                'visit_date' => now()->addDays(rand(1, 30))->format('Y-m-d'),
                'visitors' => rand(1, 4),
                'total_price' => rand(50, 300),
                'status' => ['confirmed', 'pending'][rand(0, 1)]
            ]);
        }

        $this->command->info('Test data created successfully!');
        $this->command->info('Admin login credentials:');
        $this->command->info('Super Admin: superadmin@paradiseisland.com / password123');
        $this->command->info('General Admin: admin@paradiseisland.com / admin123');
        $this->command->info('Hotel Manager: hotel@paradiseisland.com / hotel123');
        $this->command->info('Ferry Operator: ferry@paradiseisland.com / ferry123');
        $this->command->info('Park Manager: themepark@paradiseisland.com / park123');
        $this->command->info('Beach Organizer: beach@paradiseisland.com / beach123');
    }
}
