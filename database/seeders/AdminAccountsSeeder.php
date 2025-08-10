<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAdmin;
use App\Models\Admin;

class AdminAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin first
        $superAdmin = SuperAdmin::create([
            'name' => 'System Administrator',
            'email' => 'superadmin@paradiseisland.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        // Define admin accounts with their roles and permissions
        $adminAccounts = [
            [
                'name' => 'General Manager',
                'email' => 'admin@paradiseisland.com',
                'password' => Hash::make('admin123'),
                'business_type' => 'general',
                'business_name' => 'Paradise Island Management',
                'permissions' => [
                    'view_all_bookings',
                    'view_reports',
                    'manage_users',
                    'view_analytics',
                    'system_settings'
                ],
                'business_id' => null,
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'name' => 'Hotel Operations Manager',
                'email' => 'hotel@paradiseisland.com',
                'password' => Hash::make('hotel123'),
                'business_type' => 'hotel',
                'business_name' => 'Paradise Resort & Spa',
                'permissions' => [
                    'manage_hotel_bookings',
                    'view_hotel_reports',
                    'manage_room_inventory',
                    'set_hotel_pricing',
                    'view_hotel_analytics'
                ],
                'business_id' => 1, // Assuming first hotel
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'name' => 'Ferry Operations Coordinator',
                'email' => 'ferry@paradiseisland.com',
                'password' => Hash::make('ferry123'),
                'business_type' => 'ferry',
                'business_name' => 'Paradise Island Ferry Services',
                'permissions' => [
                    'manage_ferry_schedules',
                    'view_ferry_bookings',
                    'manage_ferry_capacity',
                    'set_ferry_pricing',
                    'view_ferry_reports'
                ],
                'business_id' => 1, // Assuming first ferry
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'name' => 'Theme Park Director',
                'email' => 'themepark@paradiseisland.com',
                'password' => Hash::make('park123'),
                'business_type' => 'theme_park',
                'business_name' => 'Paradise Island Theme Park',
                'permissions' => [
                    'manage_park_tickets',
                    'manage_activities',
                    'view_park_analytics',
                    'set_park_pricing',
                    'manage_park_schedule'
                ],
                'business_id' => 1, // Assuming first theme park
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
            [
                'name' => 'Beach Events Organizer',
                'email' => 'beach@paradiseisland.com',
                'password' => Hash::make('beach123'),
                'business_type' => 'beach_event',
                'business_name' => 'Paradise Island Beach Events',
                'permissions' => [
                    'manage_beach_events',
                    'view_beach_bookings',
                    'set_event_pricing',
                    'manage_event_schedule',
                    'view_event_reports'
                ],
                'business_id' => 1, // Assuming first beach event
                'is_active' => true,
                'created_by' => $superAdmin->id,
            ],
        ];

        // Create all admin accounts
        foreach ($adminAccounts as $adminData) {
            Admin::create($adminData);
        }

        $this->command->info('✅ Admin accounts seeded successfully!');
        $this->command->info('📧 Super Admin: superadmin@paradiseisland.com (password: password123)');
        $this->command->info('📧 General Admin: admin@paradiseisland.com (password: admin123)');
        $this->command->info('📧 Hotel Manager: hotel@paradiseisland.com (password: hotel123)');
        $this->command->info('📧 Ferry Operator: ferry@paradiseisland.com (password: ferry123)');
        $this->command->info('📧 Park Manager: themepark@paradiseisland.com (password: park123)');
        $this->command->info('📧 Beach Organizer: beach@paradiseisland.com (password: beach123)');
    }
}
