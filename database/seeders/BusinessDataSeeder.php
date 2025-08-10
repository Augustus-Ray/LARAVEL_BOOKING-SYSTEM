<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\Ferry;
use App\Models\ThemePark;
use App\Models\BeachEvent;
use App\Models\ParkActivity;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BusinessDataSeeder extends Seeder
{
    public function run()
    {
        // Clear existing business data to avoid duplicates
        Hotel::truncate();
        Ferry::truncate();
        ThemePark::truncate();
        ParkActivity::truncate();
        BeachEvent::truncate();

        // Get or create business owners
        $hotelOwner = User::firstOrCreate(
            ['email' => 'hotels@paradiseisland.com'],
            [
                'name' => 'Paradise Hotels Group',
                'password' => Hash::make('password123'),
            ]
        );

        $ferryOwner = User::firstOrCreate(
            ['email' => 'ferries@paradiseisland.com'],
            [
                'name' => 'Island Ferry Services',
                'password' => Hash::make('password123'),
            ]
        );

        $parkOwner = User::firstOrCreate(
            ['email' => 'parks@paradiseisland.com'],
            [
                'name' => 'Adventure Parks Ltd',
                'password' => Hash::make('password123'),
            ]
        );

        $eventOrganizer = User::firstOrCreate(
            ['email' => 'events@paradiseisland.com'],
            [
                'name' => 'Beach Events Paradise',
                'password' => Hash::make('password123'),
            ]
        );

        // Create Maldives-inspired Hotels
        $hotels = [
            [
                'name' => 'Paradise Water Villa Resort',
                'description' => 'Luxury overwater villas inspired by the Maldives, featuring private pools and direct ocean access. Wake up to pristine turquoise waters right outside your door.',
                'location' => 'North Shore, Paradise Island',
                'price_per_night' => 850.00,
                'total_rooms' => 25,
                'available_rooms' => 25,
                'amenities' => json_encode(['Overwater Villa', 'Private Pool', 'Ocean Access', 'Spa', 'Fine Dining', 'Water Sports']),
                'image_url' => 'https://images.unsplash.com/photo-1573843981267-be1999ff37cd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80',
                'contact_info' => '+960-123-0001 | reservations@paradisewatervilla.com',
                'is_active' => true,
                'owner_id' => $hotelOwner->id,
            ],
            [
                'name' => 'Crystal Lagoon Resort',
                'description' => 'Stunning beachfront resort with traditional Maldivian architecture and modern luxury. Each villa offers panoramic ocean views and private beach access.',
                'location' => 'Crystal Bay, Paradise Island',
                'price_per_night' => 650.00,
                'total_rooms' => 40,
                'available_rooms' => 40,
                'amenities' => json_encode(['Beachfront Villa', 'Private Beach', 'Infinity Pool', 'Spa', 'Restaurant', 'Snorkeling']),
                'image_url' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
                'contact_info' => '+960-123-0002 | info@crystallagoon.com',
                'is_active' => true,
                'owner_id' => $hotelOwner->id,
            ],
            [
                'name' => 'Sunset Atoll Retreat',
                'description' => 'Eco-luxury resort inspired by Maldivian coral atolls. Sustainable accommodation with world-class diving and snorkeling opportunities.',
                'location' => 'Sunset Point, Paradise Island',
                'price_per_night' => 480.00,
                'total_rooms' => 30,
                'available_rooms' => 30,
                'amenities' => json_encode(['Eco Villa', 'Coral Reef Access', 'Diving Center', 'Yoga Pavilion', 'Organic Restaurant']),
                'image_url' => 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
                'contact_info' => '+960-123-0003 | welcome@sunsetatoll.com',
                'is_active' => true,
                'owner_id' => $hotelOwner->id,
            ],
            [
                'name' => 'Aqua Dreams Resort',
                'description' => 'Maldives-style floating villas connected by wooden walkways over crystal-clear lagoons. Experience ultimate privacy and luxury.',
                'location' => 'Lagoon District, Paradise Island',
                'price_per_night' => 920.00,
                'total_rooms' => 20,
                'available_rooms' => 20,
                'amenities' => json_encode(['Floating Villa', 'Glass Floor Panels', 'Private Jetty', 'Butler Service', 'Underwater Restaurant']),
                'image_url' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2080&q=80',
                'contact_info' => '+960-123-0004 | reservations@aquadreams.com',
                'is_active' => true,
                'owner_id' => $hotelOwner->id,
            ],
            [
                'name' => 'Tropical Overwater Lodge',
                'description' => 'Authentic Maldivian experience with traditional thatched-roof villas over pristine waters. Perfect for romantic getaways and honeymoons.',
                'location' => 'Honeymoon Bay, Paradise Island',
                'price_per_night' => 750.00,
                'total_rooms' => 35,
                'available_rooms' => 35,
                'amenities' => json_encode(['Thatched Villa', 'Private Deck', 'Hammock', 'Sunset Views', 'Couples Spa', 'Romantic Dining']),
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
                'contact_info' => '+960-123-0005 | stay@tropicaloverwater.com',
                'is_active' => true,
                'owner_id' => $hotelOwner->id,
            ],
            [
                'name' => 'Blue Horizon Beach Resort',
                'description' => 'Luxurious beachfront resort with Maldivian-inspired architecture. Features multiple restaurants, spa, and water sports center.',
                'location' => 'Blue Horizon Beach, Paradise Island',
                'price_per_night' => 580.00,
                'total_rooms' => 60,
                'available_rooms' => 60,
                'amenities' => json_encode(['Beach Villa', 'Multiple Restaurants', 'Spa', 'Water Sports', 'Kids Club', 'Tennis Court']),
                'image_url' => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
                'contact_info' => '+960-123-0006 | info@bluehorizon.com',
                'is_active' => true,
                'owner_id' => $hotelOwner->id,
            ],
        ];

        foreach ($hotels as $hotelData) {
            Hotel::create($hotelData);
        }

        // Create Ferry Services
        $ferries = [
            [
                'name' => 'Paradise Island Express',
                'departure_location' => 'Main Harbor, Male City',
                'arrival_location' => 'Paradise Island Marina',
                'departure_time' => '08:00:00',
                'arrival_time' => '08:45:00',
                'capacity' => 120,
                'price' => 45.00,
                'operating_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
                'is_active' => true,
                'operator_id' => $ferryOwner->id,
            ],
            [
                'name' => 'Sunset Ferry',
                'departure_location' => 'Main Harbor, Male City',
                'arrival_location' => 'Paradise Island Marina',
                'departure_time' => '17:30:00',
                'arrival_time' => '18:15:00',
                'capacity' => 80,
                'price' => 55.00,
                'operating_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
                'is_active' => true,
                'operator_id' => $ferryOwner->id,
            ],
            [
                'name' => 'Morning Breeze Ferry',
                'departure_location' => 'Main Harbor, Male City',
                'arrival_location' => 'Paradise Island Marina',
                'departure_time' => '06:30:00',
                'arrival_time' => '07:15:00',
                'capacity' => 100,
                'price' => 50.00,
                'operating_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
                'is_active' => true,
                'operator_id' => $ferryOwner->id,
            ],
            [
                'name' => 'Luxury Yacht Transfer',
                'departure_location' => 'VIP Terminal, Male City',
                'arrival_location' => 'Paradise Island VIP Marina',
                'departure_time' => '10:00:00',
                'arrival_time' => '10:30:00',
                'capacity' => 25,
                'price' => 150.00,
                'operating_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
                'is_active' => true,
                'operator_id' => $ferryOwner->id,
            ],
        ];

        foreach ($ferries as $ferryData) {
            Ferry::create($ferryData);
        }

        // Create Theme Parks
        $themeParks = [
            [
                'name' => 'Paradise Adventure World',
                'description' => 'Premier theme park featuring thrilling rides, water attractions, and family entertainment in a tropical paradise setting.',
                'location' => 'Central Paradise Island',
                'entry_price' => 85.00,
                'opening_time' => '09:00:00',
                'closing_time' => '22:00:00',
                'operating_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
                'capacity' => 5000,
                'image_url' => 'https://images.unsplash.com/photo-1516541196182-6bdb0516ed27?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                'is_active' => true,
                'owner_id' => $parkOwner->id,
            ],
            [
                'name' => 'Aqua Adventure Park',
                'description' => 'Water-themed park with slides, lazy rivers, and marine life encounters. Perfect for families and water enthusiasts.',
                'location' => 'North Paradise Island',
                'entry_price' => 65.00,
                'opening_time' => '10:00:00',
                'closing_time' => '19:00:00',
                'operating_days' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
                'capacity' => 3000,
                'image_url' => 'https://images.unsplash.com/photo-1514162816039-3c54a0be3eec?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                'is_active' => true,
                'owner_id' => $parkOwner->id,
            ],
        ];

        foreach ($themeParks as $parkData) {
            $park = ThemePark::create($parkData);
            
            // Add activities for each park
            if ($park->name === 'Paradise Adventure World') {
                $activities = [
                    [
                        'name' => 'Dragon Coaster',
                        'description' => 'High-speed roller coaster with loops and corkscrews through tropical scenery.',
                        'type' => 'ride',
                        'price' => 25.00,
                        'duration_minutes' => 3,
                        'capacity_per_session' => 24,
                        'min_age' => 12,
                        'requirements' => json_encode(['minimum_height' => '140cm']),
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Paradise Zip Line',
                        'description' => 'Canopy zip line adventure through lush tropical rainforest with ocean views.',
                        'type' => 'experience',
                        'price' => 35.00,
                        'duration_minutes' => 45,
                        'capacity_per_session' => 8,
                        'min_age' => 8,
                        'requirements' => json_encode(['minimum_height' => '120cm', 'weight_limit' => '120kg']),
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Tropical Safari Tour',
                        'description' => 'Guided tour through exotic animal habitats with educational presentations.',
                        'type' => 'experience',
                        'price' => 20.00,
                        'duration_minutes' => 60,
                        'capacity_per_session' => 30,
                        'min_age' => 0,
                        'is_active' => true,
                    ],
                ];
            } else {
                $activities = [
                    [
                        'name' => 'Mega Water Slide',
                        'description' => 'Giant water slide with multiple lanes and splash zones.',
                        'type' => 'ride',
                        'price' => 15.00,
                        'duration_minutes' => 5,
                        'capacity_per_session' => 20,
                        'min_age' => 6,
                        'requirements' => json_encode(['minimum_height' => '110cm']),
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Lazy River Float',
                        'description' => 'Relaxing float through scenic waterways with tropical landscaping.',
                        'type' => 'experience',
                        'price' => 10.00,
                        'duration_minutes' => 30,
                        'capacity_per_session' => 50,
                        'min_age' => 0,
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Dolphin Encounter',
                        'description' => 'Interactive experience with trained dolphins in natural lagoon setting.',
                        'type' => 'experience',
                        'price' => 75.00,
                        'duration_minutes' => 45,
                        'capacity_per_session' => 12,
                        'min_age' => 5,
                        'requirements' => json_encode(['swimming_ability' => 'required', 'health_clearance' => 'required']),
                        'is_active' => true,
                    ],
                ];
            }

            foreach ($activities as $activityData) {
                $activityData['theme_park_id'] = $park->id;
                ParkActivity::create($activityData);
            }
        }

        // Create Beach Events
        $beachEvents = [
            [
                'name' => 'Sunset Beach BBQ Festival',
                'description' => 'Weekly beachside barbecue with live music, local cuisine, and stunning sunset views over the Indian Ocean.',
                'location' => 'Sunset Beach, Paradise Island',
                'type' => 'dining',
                'price' => 45.00,
                'start_time' => '2025-08-15 18:00:00',
                'end_time' => '2025-08-15 22:00:00',
                'capacity' => 200,
                'equipment_included' => json_encode(['BBQ Equipment', 'Seating', 'Music System']),
                'image_url' => 'https://images.unsplash.com/photo-1544148103-0773bf10d330?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                'is_active' => true,
                'organizer_id' => $eventOrganizer->id,
            ],
            [
                'name' => 'Moonlight Beach Yoga',
                'description' => 'Peaceful yoga session on the beach under the moonlight with meditation and wellness activities.',
                'location' => 'Tranquil Bay, Paradise Island',
                'type' => 'adventure',
                'price' => 25.00,
                'start_time' => '2025-08-20 20:00:00',
                'end_time' => '2025-08-20 21:30:00',
                'capacity' => 50,
                'equipment_included' => json_encode(['Yoga Mats', 'Meditation Cushions', 'Blankets']),
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                'is_active' => true,
                'organizer_id' => $eventOrganizer->id,
            ],
            [
                'name' => 'Beach Volleyball Tournament',
                'description' => 'Competitive beach volleyball tournament with prizes, refreshments, and beach party atmosphere.',
                'location' => 'Sports Beach, Paradise Island',
                'type' => 'sports',
                'price' => 20.00,
                'start_time' => '2025-08-25 16:00:00',
                'end_time' => '2025-08-25 20:00:00',
                'capacity' => 150,
                'equipment_included' => json_encode(['Volleyball Nets', 'Balls', 'Refreshments']),
                'image_url' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                'is_active' => true,
                'organizer_id' => $eventOrganizer->id,
            ],
            [
                'name' => 'Tropical Music Festival',
                'description' => 'Three-day music festival featuring local and international artists with food vendors and craft stalls.',
                'location' => 'Festival Beach, Paradise Island',
                'type' => 'music',
                'price' => 75.00,
                'start_time' => '2025-09-01 14:00:00',
                'end_time' => '2025-09-01 23:00:00',
                'capacity' => 1000,
                'equipment_included' => json_encode(['Sound System', 'Stage', 'Food Vendors', 'Craft Stalls']),
                'image_url' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                'is_active' => true,
                'organizer_id' => $eventOrganizer->id,
            ],
            [
                'name' => 'Cultural Heritage Night',
                'description' => 'Traditional Maldivian cultural performances, local crafts demonstration, and authentic cuisine.',
                'location' => 'Heritage Beach, Paradise Island',
                'type' => 'cultural',
                'price' => 35.00,
                'start_time' => '2025-09-10 19:00:00',
                'end_time' => '2025-09-10 22:30:00',
                'capacity' => 250,
                'equipment_included' => json_encode(['Performance Stage', 'Traditional Costumes', 'Craft Displays']),
                'image_url' => 'https://images.unsplash.com/photo-1533854775446-95c4609da544?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
                'is_active' => true,
                'organizer_id' => $eventOrganizer->id,
            ],
        ];

        foreach ($beachEvents as $eventData) {
            BeachEvent::create($eventData);
        }

        $this->command->info('Business data seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . count($hotels) . ' Maldives-inspired hotels with beautiful images');
        $this->command->info('- ' . count($ferries) . ' ferry services');
        $this->command->info('- ' . count($themeParks) . ' theme parks with activities');
        $this->command->info('- ' . count($beachEvents) . ' beach events');
        $this->command->info('- 4 business owner accounts');
    }
}
