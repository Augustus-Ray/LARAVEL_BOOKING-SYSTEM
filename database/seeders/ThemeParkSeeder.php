<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeParkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample admin user
        $admin = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@paradise.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'address' => 'Paradise Island Administration',
        ]);

        // Create sample hotel owner
        $hotelOwner = \App\Models\User::create([
            'name' => 'Hotel Manager',
            'email' => 'hotel@paradise.com',
            'password' => bcrypt('password'),
            'role' => 'hotel_owner',
            'phone' => '+1234567891',
            'address' => 'Paradise Resort Management',
        ]);

        // Create sample park owner
        $parkOwner = \App\Models\User::create([
            'name' => 'Park Director',
            'email' => 'park@paradise.com',
            'password' => bcrypt('password'),
            'role' => 'park_owner',
            'phone' => '+1234567892',
            'address' => 'Theme Park Operations',
        ]);

        // Create sample visitor
        $visitor = \App\Models\User::create([
            'name' => 'John Visitor',
            'email' => 'visitor@example.com',
            'password' => bcrypt('password'),
            'role' => 'visitor',
            'phone' => '+1234567893',
            'address' => '123 Main Street, City',
        ]);

        // Create sample hotels
        $hotels = [
            [
                'name' => 'Paradise Resort & Spa',
                'description' => 'Luxury beachfront resort with world-class amenities and stunning ocean views. Perfect for families and couples.',
                'location' => 'North Beach Paradise Island',
                'latitude' => 25.7617,
                'longitude' => -80.1918,
                'price_per_night' => 299.99,
                'total_rooms' => 150,
                'available_rooms' => 120,
                'amenities' => ['spa', 'pool', 'restaurant', 'wifi', 'parking', 'beach_access'],
                'image_url' => 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?ixlib=rb-4.0.3',
                'contact_info' => 'reservations@paradiseresort.com | +1-800-PARADISE',
                'owner_id' => $hotelOwner->id,
            ],
            [
                'name' => 'Island Breeze Hotel',
                'description' => 'Comfortable mid-range accommodation with beautiful garden views and easy access to all island attractions.',
                'location' => 'Central Paradise Island',
                'latitude' => 25.7617,
                'longitude' => -80.1818,
                'price_per_night' => 159.99,
                'total_rooms' => 80,
                'available_rooms' => 65,
                'amenities' => ['pool', 'restaurant', 'wifi', 'parking'],
                'image_url' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3',
                'contact_info' => 'info@islandbreeze.com | +1-800-BREEZE',
                'owner_id' => $hotelOwner->id,
            ],
            [
                'name' => 'Budget Paradise Inn',
                'description' => 'Affordable and clean accommodation perfect for budget-conscious travelers who want to experience paradise.',
                'location' => 'South Paradise Island',
                'latitude' => 25.7517,
                'longitude' => -80.1918,
                'price_per_night' => 89.99,
                'total_rooms' => 50,
                'available_rooms' => 40,
                'amenities' => ['wifi', 'parking'],
                'image_url' => 'https://images.unsplash.com/photo-1564501049412-61c2a3083791?ixlib=rb-4.0.3',
                'contact_info' => 'budget@paradiseinn.com | +1-800-BUDGET',
                'owner_id' => $hotelOwner->id,
            ],
        ];

        foreach ($hotels as $hotelData) {
            \App\Models\Hotel::create($hotelData);
        }

        // Create sample theme park
        $themePark = \App\Models\ThemePark::create([
            'name' => 'Adventure Paradise Theme Park',
            'description' => 'The ultimate theme park experience with thrilling rides, amazing shows, and unforgettable adventures for all ages.',
            'location' => 'Adventure Island',
            'latitude' => 25.7717,
            'longitude' => -80.2018,
            'entry_price' => 89.99,
            'opening_time' => '09:00',
            'closing_time' => '22:00',
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
            'capacity' => 5000,
            'image_url' => 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?ixlib=rb-4.0.3',
            'owner_id' => $parkOwner->id,
        ]);

        // Create sample park activities
        $activities = [
            [
                'theme_park_id' => $themePark->id,
                'name' => 'Space Exploration Roller Coaster',
                'description' => 'A high-speed roller coaster that takes visitors on a simulated journey through space with stunning visual effects.',
                'type' => 'ride',
                'price' => 25.00,
                'duration_minutes' => 5,
                'capacity_per_session' => 24,
                'min_age' => 12,
                'requirements' => ['height' => '48 inches minimum', 'health' => 'No heart conditions'],
                'image_url' => 'https://images.unsplash.com/photo-1594736797933-d0401ba2fe65?ixlib=rb-4.0.3',
                'start_time' => '09:30',
                'end_time' => '21:30',
            ],
            [
                'theme_park_id' => $themePark->id,
                'name' => 'Glow-in-the-Dark Coral Ride',
                'description' => 'A magical night-time ride inspired by bioluminescent coral reefs with immersive underwater experience.',
                'type' => 'ride',
                'price' => 20.00,
                'duration_minutes' => 8,
                'capacity_per_session' => 16,
                'min_age' => 6,
                'requirements' => ['height' => '36 inches minimum'],
                'image_url' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?ixlib=rb-4.0.3',
                'start_time' => '19:00',
                'end_time' => '21:45',
            ],
            [
                'theme_park_id' => $themePark->id,
                'name' => 'Superhero 4D Experience',
                'description' => 'An immersive 4D cinema experience where you become the superhero saving the world.',
                'type' => 'experience',
                'price' => 15.00,
                'duration_minutes' => 20,
                'capacity_per_session' => 40,
                'min_age' => 5,
                'requirements' => [],
                'image_url' => 'https://images.unsplash.com/photo-1635870664257-430ee25df09d?ixlib=rb-4.0.3',
                'start_time' => '10:00',
                'end_time' => '21:00',
            ],
            [
                'theme_park_id' => $themePark->id,
                'name' => 'Water Sports Arena',
                'description' => 'Engage in jet skiing, paddleboarding, kayaking, and snorkeling in our specially designed water arena.',
                'type' => 'experience',
                'price' => 35.00,
                'duration_minutes' => 60,
                'capacity_per_session' => 12,
                'min_age' => 10,
                'requirements' => ['swimming' => 'Basic swimming ability required'],
                'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3',
                'start_time' => '10:00',
                'end_time' => '18:00',
            ],
        ];

        foreach ($activities as $activityData) {
            \App\Models\ParkActivity::create($activityData);
        }

        // Create sample beach events
        $eventOrganizer = \App\Models\User::create([
            'name' => 'Event Coordinator',
            'email' => 'events@paradise.com',
            'password' => bcrypt('password'),
            'role' => 'event_organizer',
            'phone' => '+1234567894',
            'address' => 'Paradise Events Department',
        ]);

        $beachEvents = [
            [
                'name' => 'Sunset Beach Volleyball Tournament',
                'description' => 'Competitive beach volleyball tournament with prizes for winners. All skill levels welcome.',
                'location' => 'Central Beach Paradise Island',
                'latitude' => 25.7617,
                'longitude' => -80.1750,
                'type' => 'sports',
                'price' => 25.00,
                'start_time' => now()->addDays(7)->setTime(16, 0),
                'end_time' => now()->addDays(7)->setTime(20, 0),
                'capacity' => 32,
                'equipment_included' => ['volleyball', 'net', 'referee'],
                'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3',
                'organizer_id' => $eventOrganizer->id,
            ],
            [
                'name' => 'Island Music Festival',
                'description' => 'A magical evening of live music featuring local and international artists under the stars.',
                'location' => 'Paradise Amphitheater',
                'latitude' => 25.7557,
                'longitude' => -80.1818,
                'type' => 'music',
                'price' => 75.00,
                'start_time' => now()->addDays(14)->setTime(19, 0),
                'end_time' => now()->addDays(14)->setTime(23, 0),
                'capacity' => 500,
                'equipment_included' => ['seating', 'sound_system', 'lighting'],
                'image_url' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3',
                'organizer_id' => $eventOrganizer->id,
            ],
            [
                'name' => 'Snorkeling Adventure Tour',
                'description' => 'Guided snorkeling tour to explore the beautiful coral reefs around Paradise Island.',
                'location' => 'North Reef Paradise Island',
                'latitude' => 25.7717,
                'longitude' => -80.1650,
                'type' => 'adventure',
                'price' => 45.00,
                'start_time' => now()->addDays(3)->setTime(10, 0),
                'end_time' => now()->addDays(3)->setTime(14, 0),
                'capacity' => 15,
                'equipment_included' => ['snorkel_gear', 'fins', 'guided_tour', 'safety_briefing'],
                'image_url' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3',
                'requirements' => 'Basic swimming ability required. Minimum age 8 years.',
                'organizer_id' => $eventOrganizer->id,
            ],
        ];

        foreach ($beachEvents as $eventData) {
            \App\Models\BeachEvent::create($eventData);
        }

        // Create sample ferry
        $ferryOperator = \App\Models\User::create([
            'name' => 'Ferry Captain',
            'email' => 'ferry@paradise.com',
            'password' => bcrypt('password'),
            'role' => 'ferry_operator',
            'phone' => '+1234567895',
            'address' => 'Paradise Ferry Terminal',
        ]);

        \App\Models\Ferry::create([
            'name' => 'Paradise Express',
            'departure_location' => 'Paradise Island Main Port',
            'arrival_location' => 'Adventure Island Theme Park Port',
            'departure_time' => '09:00',
            'arrival_time' => '09:30',
            'capacity' => 100,
            'price' => 15.00,
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
            'operator_id' => $ferryOperator->id,
        ]);

        // Create sample advertisements
        $advertisements = [
            [
                'title' => 'Paradise Resort Special Offer!',
                'description' => 'Book 3 nights and get the 4th night FREE! Limited time offer for new guests.',
                'image_url' => 'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?ixlib=rb-4.0.3',
                'link_url' => route('hotels.index'),
                'type' => 'hotel',
                'position' => 'banner',
                'priority' => 10,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'advertiser_id' => $hotelOwner->id,
            ],
            [
                'title' => 'Adventure Paradise - Grand Opening!',
                'description' => 'Experience the thrill of our new Space Exploration Roller Coaster! First 100 visitors get 50% off.',
                'image_url' => 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?ixlib=rb-4.0.3',
                'link_url' => route('theme-parks.index'),
                'type' => 'theme_park',
                'position' => 'featured',
                'priority' => 9,
                'start_date' => now(),
                'end_date' => now()->addDays(14),
                'advertiser_id' => $parkOwner->id,
            ],
        ];

        foreach ($advertisements as $adData) {
            \App\Models\Advertisement::create($adData);
        }
    }
}
